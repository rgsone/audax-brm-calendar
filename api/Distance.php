<?php

namespace Rgsone\BrmCalendar;

enum Distance: int
{
	case ALL = 0;
	case TWO_HUNDRED = 200;
	case THREE_HUNDRED = 300;
	case FOUR_HUNDRED = 400;
	case SIX_HUNDRED = 600;
	case ONE_THOUSAND = 1000;

	public function frenchName(): string
	{
		return match($this)
		{
			Distance::ALL => 'Toutes distances',
			Distance::TWO_HUNDRED => '200 km',
			Distance::THREE_HUNDRED => '300 km',
			Distance::FOUR_HUNDRED => '400 km',
			Distance::SIX_HUNDRED => '600 km',
			Distance::ONE_THOUSAND => '1000 km'
		};
	}

	public static function has(int $value): bool
	{
		return match($value) {
			Distance::ALL->value,
			Distance::TWO_HUNDRED->value,
			Distance::THREE_HUNDRED->value,
			Distance::FOUR_HUNDRED->value,
			Distance::SIX_HUNDRED->value,
			Distance::ONE_THOUSAND->value => true,
			default => false
		};
	}
}
