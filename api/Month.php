<?php

namespace Rgsone\BrmCalendar;

enum Month: int
{
	case ALL = 0;
	case JANUARY = 1;
	case FEBRUARY = 2;
	case MARCH = 3;
	case APRIL = 4;
	case MAY = 5;
	case JUNE = 6;
	case JULY = 7;
	case AUGUST = 8;
	case SEPTEMBER = 9;
	case OCTOBER = 10;
	case NOVEMBER = 11;
	case DECEMBER = 12;

	public function frenchName(): string
	{
		return match($this)
		{
			Month::ALL => 'Toute l\'année',
			Month::JANUARY => 'Janvier',
			Month::FEBRUARY => 'Février',
			Month::MARCH => 'Mars',
			Month::APRIL => 'Avril',
			Month::MAY => 'Mai',
			Month::JUNE => 'Juin',
			Month::JULY => 'Juillet',
			Month::AUGUST => 'Août',
			Month::SEPTEMBER => 'Septembre',
			Month::OCTOBER => 'Octobre',
			Month::NOVEMBER => 'Novembre',
			Month::DECEMBER => 'Décembre'
		};
	}

	public static function has(int $value): bool
	{
		return match($value) {
			Month::ALL->value,
			Month::JANUARY->value,
			Month::FEBRUARY->value,
			Month::MARCH->value,
			Month::APRIL->value,
			Month::MAY->value,
			Month::JUNE->value,
			Month::JULY->value,
			Month::AUGUST->value,
			Month::SEPTEMBER->value,
			Month::OCTOBER->value,
			Month::NOVEMBER->value,
			Month::DECEMBER->value => true,
			default => false
		};
	}
}
