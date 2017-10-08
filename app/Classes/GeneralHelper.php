<?php

namespace App\Classes;

class GeneralHelper {

	public static function getDamageTypes() {
		return [
			'bludgeoning' => 'Bludgeoning',
			'slashing' => 'Slashing',
			'piercing' => 'Piercing',
			'acid' => 'Acid',
			'cold' => 'Cold',
			'fire' => 'Fire',
			'force' => 'Force',
			'lightning' => 'Lightning',
			'necrotic' => 'Necrotic',
			'poison' => 'Poison',
			'psychic' => 'Psychic',
			'radiant' => 'Radiant',
			'thunder' => 'Thunder',
			'nonmagical' => 'Non-magical',
			'magical' => 'Magical',
			'silver' => 'Silver',
			'adamantine' => 'Adamantine',
		];
	}

	public static function getConditions() {
		return [
			'blinded' => 'Blinded',
			'charmed' => 'Charmed',
			'deafened' => 'Deafened',
			'exhaustion' => 'Exhaustion',
			'fatigued' => 'Fatigued',
			'frightened' => 'Frightened',
			'grappled' => 'Grappled',
			'incapacitated' => 'Incapacitated',
			'invisible' => 'Invisible',
			'paralyzed' => 'Paralyzed',
			'petrified' => 'Petrified',
			'poisoned' => 'Poisoned',
			'prone' => 'Prone',
			'restrained' => 'Restrained',
			'stunned' => 'Stunned',
			'unconscious' => 'Unconscious',
		];
	}

	public static function getLanguages() {
		return [
			'common' => 'Common',
			'druidic' => 'Druidic',
			'dwarvish' => 'Dwarvish',
			'elvish' => 'Elvish',
			'giant' => 'Giant',
			'gnomish' => 'Gnomish',
			'goblin' => 'Goblin',
			'halfling' => 'Halfling',
			'orc' => 'Orc',
			'abyssal' => 'Abyssal',
			'celestial' => 'Celestial',
			'draconic' => 'Draconic',
			'deep speech' => 'Deep Speech',
			'infernal' => 'Infernal',
			'primordial' => 'Primordial',
			'sylvan' => 'Sylvan',
			'undercommon' => 'Undercommon',
			'telepathy' => 'Telepathy',
			'auran' => 'Auran',
			'aquan' => 'Aquan',
			'ignan' => 'Ignan',
			'terran' => 'Terran',
		];
	}

	public static function getDiceSizes() {
		return [
			'd4' => 'd4',
			'd6' => 'd6',
			'd8' => 'd8',
			'd10' => 'd10',
			'd12' => 'd12',
			'd20' => 'd20',
			'd100' => 'd100',
		];
	}

	public static function getAbilities() {
		return [
			'strength' => 'Strength',
			'dexterity' => 'Dexterity',
			'constitution' => 'Constitution',
			'intelligence' => 'Intelligence',
			'wisdom' => 'Wisdom',
			'charisma' => 'Charisma',
		];
	}

	public static function getSavingThrows() {
		return [
			'str_save' => 'Strength',
			'dex_save' => 'Dexterity',
			'con_save' => 'Constitution',
			'int_save' => 'Intelligence',
			'wis_save' => 'Wisdom',
			'cha_save' => 'Charisma',
		];
	}

	public static function getClasses() {
		return [
			'Barbarian' => 'Barbarian',
			'Bard' => 'Bard',
			'Cleric' => 'Cleric',
			'Druid' => 'Druid',
			'Fighter' => 'Fighter',
			'Monk' => 'Monk',
			'Paladin' => 'Paladin',
			'Ranger' => 'Ranger',
			'Rogue' => 'Rogue',
			'Sorcerer' => 'Sorcerer',
			'Warlock' => 'Warlock',
			'Wizard' => 'Wizard',
		];
	}

	public static function getRaces() {
		return [
			'Dragonborn' => 'Dragonborn',
			'Dwarf' => 'Dwarf',
			'Elf' => 'Elf',
			'Genasi' => 'Genasi',
			'Gnome' => 'Gnome',
			'Goliath' => 'Goliath',
			'Halfling' => 'Halfling',
			'Half Elf' => 'Half Elf',
			'Half Orc' => 'Half Orc',
			'Human' => 'Human',
			'Tiefling' => 'Tiefling',
		];
	}

}
