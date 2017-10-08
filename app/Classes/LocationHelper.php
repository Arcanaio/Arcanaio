<?php

namespace App\Classes;

class LocationHelper {

	public static function getTypes() {
		return [
			'Shop' => 'Shop',
			'Tavern/Inn' => 'Tavern/Inn',
			'Castle/Keep' => 'Castle/Keep',
			'Temple' => 'Temple',
			'Building (Other)' => 'Building (Other)',
			'Biome (Other)' => 'Biome (Other)',
			'Village/Town/City' => 'Village/Town/City',
			'Kingdom' => 'Kingdom',
			'Plane' => 'Plane',
			'Continent' => 'Continent',
			'Island' => 'Island',
			'Forest' => 'Forest',
			'Mountain' => 'Mountain',
			'Grassland' => 'Grassland',
			'Swamp' => 'Swamp',
			'Arctic' => 'Arctic',
			'Desert' => 'Desert',
			'Ocean/Sea/Lake' => 'Ocean/Sea/Lake',
			'Dungeon' => 'Dungeon',
			'Cavern/Mine' => 'Cavern/Mine',
			'Underdark' => 'Underdark',
			'Celestial' => 'Celestial',
		];
	}

	public static function getShopTypes() {
		return [
			'Armorer' => 'Armorer',
			'Weaponsmith' => 'Weaponsmith',
			'Alchemist' => 'Alchemist',
			'General' => 'General',
			'Food' => 'Food',
			'Magic' => 'Magic',
			'Stables' => 'Stables',
			'Shipwright' => 'Shipwright',
			'Jeweler' => 'Jeweler',
			'Clothing' => 'Clothing',
			'Other' => 'Other',
		];
	}

	public static function getEnvironments() {
		return [
			'Any' => 'Any',
			'Urban' => 'Urban',
			'Forest' => 'Forest',
			'Hill' => 'Hill',
			'Mountain' => 'Mountain',
			'Grassland' => 'Grassland',
			'Swamp' => 'Swamp',
			'Coastal' => 'Coastal',
			'Underwater' => 'Underwater',
			'Arctic' => 'Arctic',
			'Desert' => 'Desert',
			'Planar' => 'Planar',
			'Underdark' => 'Underdark',
		];
	}

}
