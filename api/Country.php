<?php

namespace Rgsone\BrmCalendar;

enum Country: string
{
	case WORLD = 'world';
	case GB = 'gb';
	case FR = 'fr';
	case BE = 'be';
	case NL = 'nl';
	case DE = 'de';
	case CH = 'ch';
	case IT = 'it';
	case ES = 'es';

	public function frenchName(): string
	{
		return match($this)
		{
			Country::WORLD => 'Monde',
			Country::GB => 'Royaume-Uni',
			Country::FR => 'France',
			Country::BE => 'Belgique',
			Country::NL => 'Pays-Bas',
			Country::DE => 'Allemagne',
			Country::CH => 'Suisse',
			Country::IT => 'Italie',
			Country::ES => 'Espagne'
		};
	}

	public function dataName(): string
	{
		return match($this)
		{
			Country::WORLD => 'World',
			Country::GB => 'United Kingdom',
			Country::FR => 'France',
			Country::BE => 'Belgium',
			Country::NL => 'The Netherlands',
			Country::DE => 'Allemagne',
			Country::CH => 'Suisse',
			Country::IT => 'Italy',
			Country::ES => 'Spain'
		};
	}

	public static function has(string $value): bool
	{
		return match($value) {
			Country::WORLD->value,
			Country::GB->value,
			Country::FR->value,
			Country::BE->value,
			Country::NL->value,
			Country::DE->value,
			Country::CH->value,
			Country::IT->value,
			Country::ES->value => true,
			default => false
		};
	}


}
