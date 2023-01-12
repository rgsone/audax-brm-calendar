<?php

namespace Rgsone\BrmCalendar;

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Api
{
	private Request $req;
	private ?int $month = null;
	private ?int $distance = null;
	private ?int $country = null;

	public function __construct(Request $request)
	{
		$this->req = $request;
		$this->boot();
	}

	private function boot(): void
	{
		$db = new DB();
		$db->addConnection([
			'driver'    => 'sqlite',
			'database'  => dirname(__DIR__) . '/database/data.db',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => ''
		]);
		$db->setAsGlobal();
	}

	private function prepareDbRequest(): Builder
	{
		$req = DB::table('races');

		$distance = $this->req->request->has('distance') ? Distance::tryFrom($this->req->request->getInt('distance')) : null;
		$month = $this->req->request->has('month') ? Month::tryFrom($this->req->request->getInt('month')) : null;
		$country = $this->req->request->has('country') ? Country::tryFrom($this->req->request->getAlpha('country')) : null;

		if (!is_null($distance) && $distance !== Distance::ALL)
			$req->where('distance', '=', $distance);

		if (!is_null($month) && $month !== Month::ALL) {
			$c = Carbon::createFromDate(2023, $month->value);
			$req->whereDate('date', '>=', $c->firstOfMonth()->startOfDay());
			$req->whereDate('date', '<=', $c->lastOfMonth()->endOfDay());
		}

		if (!is_null($country) && $country !== Country::WORLD) {
			$req->where('country', '=', $country->dataName());
		}

		return $req;
	}

	private function getPrevPageNumber(LengthAwarePaginator $res): int|null
	{
		$number = null;

		if ($res->currentPage() > 1) {
			$number = $res->currentPage() <= $res->lastPage() ? $res->currentPage() - 1 : $res->lastPage();
		}

		return $number;
	}

	private function getNextPageNumber(LengthAwarePaginator $res): int|null
	{
		$number = null;

		if ($res->currentPage() < $res->lastPage()) {
			$number = $res->currentPage() >= 1 ? $res->currentPage() + 1 : $res->lastPage();
		}

		return $number;
	}

	public function getData(): JsonResponse
	{
		$page = $this->req->request->has('page') && $this->req->request->getInt('page') > 0 ?
			$this->req->request->getInt('page') : 1;

		$req = $this->prepareDbRequest();
		$req = $req->orderBy('datetime')->orderBy('distance');

		$res = $req->paginate(
				perPage: 20,
				columns: [
					'id',
					'date',
					'distance',
					'contact',
					'contact_mail',
					'country',
					'web_site',
					'city',
					'county',
					'region',
					'roadmap',
					'elevation',
					'club_name'
				],
				page: $page
			);

		$res->getCollection()->map(function ($item, $key) {

			$item->date = Carbon::createFromFormat('Y-m-d', $item->date)->format('d/m');
			//$item->contact = htmlspecialchars($item->contact);
			//$item->contact_mail = htmlspecialchars($item->contact_mail);
			$item->web_site = htmlspecialchars($item->web_site);
			//$item->city = htmlspecialchars($item->city);
			//$item->roadmap = htmlspecialchars($item->roadmap);
			$item->club_name = htmlspecialchars($item->club_name);

			return $item;
		});

		$data = [
			'pagination' => [
				'items' => [
					'totalItems' => $res->total(),
					'perPageItems' => $res->perPage(),
					'currentPageItemsCount' => $res->count(),
					'pageFirstItemIndex' => $res->firstItem(),
					'pageLastItemIndex' => $res->lastItem(),
				],
				'pages' => [
					'isFirstPage' => $res->onFirstPage(),
					'isLastPage' => $res->currentPage() === $res->lastPage(),
					'hasPages' => $res->hasPages(),
					'hasMorePages' => $res->hasMorePages(),
					'firstPageNumber' => 1,
					'lastPageNumber' => $res->lastPage(),
					'currentPageNumber' => $res->currentPage(),
					'prevPageNumber' => $this->getPrevPageNumber($res),
					'nextPageNumber' => $this->getNextPageNumber($res),
				]
			],
			'data' => $res->isEmpty() ? null : $res->items()
		];

		// clean & sanitize data

		$response = new JsonResponse();
		$response->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
		$response->setData($data);

		return $response;
	}
}
