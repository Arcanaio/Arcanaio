<?php

namespace App\Classes;

class LevelTable {
   private $table ;
   
    public function __construct()
    {
        $this->table = [
            "1" => new SpellTable,
            "2" => new SpellTable,
            "3" => new SpellTable,
            "4" => new SpellTable,
            "5" => new SpellTable,
            "6" => new SpellTable,
            "7" => new SpellTable,
            "8" => new SpellTable,
            "9" => new SpellTable,
            "10" => new SpellTable,
            "11" => new SpellTable,
            "12" => new SpellTable,
            "13" => new SpellTable,
            "14" => new SpellTable,
            "15" => new SpellTable,
            "16" => new SpellTable,
            "17" => new SpellTable,
            "18" => new SpellTable,
            "19" => new SpellTable,
            "20" => new SpellTable
        ];
    }
    public function index($idx){
    
		if ($idx > 20) {
			return $this->table['20'];
		}
		if ($idx < 0) {
            return 0;
		}

        if ($this->table[$idx]->index("0-level (Cantrip)") == 0) {
			return $this->index((string)(((int)$idx)-1));
		}
		
		return $this->table[$idx]->getContent();
    }
    
    public function gainSpellSlotAtLevel($spell_level,$user_level, $amount){
        for($i = $user_level; $i != 20; $i++){
            \Log::warning($this->table[$i]->val);
            $this->table[(string)$i]->gainSpellSlot($spell_level, $amount);
        }
    }
   
    public function getContent(){
        $disp = [
            "1" => $this->table["1"]->getContent(),
            "2" => $this->table["2"]->getContent(),
            "3" => $this->table["3"]->getContent(),
            "4" => $this->table["4"]->getContent(),
            "5" => $this->table["5"]->getContent(),
            "6" => $this->table["6"]->getContent(),
            "7" => $this->table["7"]->getContent(),
            "8" => $this->table["8"]->getContent(),
            "9" => $this->table["9"]->getContent(),
            "10" => $this->table["10"]->getContent(),
            "11" => $this->table["11"]->getContent(),
            "12" => $this->table["12"]->getContent(),
            "13" => $this->table["13"]->getContent(),
            "14" => $this->table["14"]->getContent(),
            "15" => $this->table["15"]->getContent(),
            "16" => $this->table["16"]->getContent(),
            "17" => $this->table["17"]->getContent(),
            "18" => $this->table["18"]->getContent(),
            "19" => $this->table["19"]->getContent(),
            "20" => $this->table["20"]->getContent(),
        ];
        return $disp;
    }
}

class SpellTable {
    private $edited = False;
    public $val = "NO";
    public $val2 = "YES";
    private $spell_slots = ['0-level (Cantrip)' => "0", 
                '1st-level' => "0",
                '2nd-level' => "0", 
                '3rd-level' => "0", 
                '4th-level' => "0", 
                '5th-level' => "0", 
                '6th-level' => "0", 
                '7th-level' => "0", 
                '8th-level' => "0", 
                '9th-level' => "0"
            ];
    private $spells_known = 0;

    public function getContent(){
        return $this->spell_slots;
    }

    public function isEdited(){
        return $this->edited;
    }
    public function seed($other_table){
        $this->spell_slots = $other_table->getContent();
    }
    public function gainSpellSlot($spell_level, $amount){
	    $this->edited = True;
        $this->spell_slots[$spell_level] = (string)((int)($this->spell_slots[$spell_level]) + (int)$amount);
    }
    public function index($idx){
        return $this->spell_slots[$idx];
    }
    
    
}

class SpellHelper {

	public function getSnippet($name) {
		if (strpos($name, '(') !== false) {
			$s = explode("(", $name);
			$name = trim($s[0]);
		}

		$spell = Cache::remember('spells_get_' . $name, 60, function () use ($name) {
			return Spell::where('name', $name)->first();
		});

		if ($spell) {
			return view('spell.snippet', compact('spell'))->render();
		} else {
			return null;
		}

	}

	public static function getSpellAbilities() {
		return [
			'intelligence' => 'Intelligence',
			'wisdom' => 'Wisdom',
			'charisma' => 'Charisma',
		];
	}

	public static function getClassSpellAbilities() {
		return [
			'Wizard' => 'Intelligence',
			'Rogue' => 'Intelligence',
			'Fighter' => 'Intelligence',
			'Cleric' => 'Wisdom',
			'Druid' => 'Wisdom',
			'Ranger' => 'Wisdom',
			'Bard' => 'Charisma',
			'Paladin' => 'Charisma',
			'Sorcerer' => 'Charisma',
			'Warlock' => 'Charisma',
		];
	}

	public static function getSpellLevels() {
		return [
			'0-level (Cantrip)' => '0-level (Cantrip)',
			'1st-level' => '1st-level',
			'2nd-level' => '2nd-level',
			'3rd-level' => '3rd-level',
			'4th-level' => '4th-level',
			'5th-level' => '5th-level',
			'6th-level' => '6th-level',
			'7th-level' => '7th-level',
			'8th-level' => '8th-level',
			'9th-level' => '9th-level',
		];
	}

	public static function getSpellSchools() {
		return [
			'Conjuration' => 'Conjuration',
			'Abjuration' => 'Abjuration',
			'Divination' => 'Divination',
			'Enchantment' => 'Enchantment',
			'Evocation' => 'Evocation',
			'Illusion' => 'Illusion',
			'Necromancy' => 'Necromancy',
			'Transmutation' => 'Transmutation',
		];
	}

	public static function getSpellClasses() {
		return [
			'Bard' => 'Bard',
			'Cleric' => 'Cleric',
			'Druid' => 'Druid',
			'Paladin' => 'Paladin',
			'Ranger' => 'Ranger',
			'Sorcerer' => 'Sorcerer',
			'Warlock' => 'Warlock',
			'Wizard' => 'Wizard',
		];
	}

	public static function getSpellComponents() {
		return [
			'V' => 'Verbal',
			'S' => 'Somatic',
			'M' => 'Material',
		];
	}

	public static function getSpellsKnown($class, $level, $spell_ability) {
		if ($class == 'Bard') {
			$spells_known = [
				'1' => '4',
				'2' => '5',
				'3' => '6',
				'4' => '7',
				'5' => '8',
				'6' => '9',
				'7' => '10',
				'8' => '11',
				'9' => '12',
				'10' => '14',
				'11' => '15',
				'12' => '15',
				'13' => '16',
				'14' => '18',
				'15' => '19',
				'16' => '19',
				'17' => '20',
				'18' => '22',
				'19' => '22',
				'20' => '22',
			];

			return $spells_known[$level];

		} elseif ($class == 'Cleric' || $class == 'Druid' || $class == 'Wizard') {
			if (($level + $spell_ability) > 0) {
				return $level + $spell_ability;
			} else {
				return 1;
			}
		} elseif ($class == 'Paladin') {
			if (($level + $spell_ability) > 0) {
				return floor($level / 2) + $spell_ability;
			} else {
				return 1;
			}
		} elseif ($class == 'Ranger') {
			$spells_known = [
				'1' => '-',
				'2' => '2',
				'3' => '3',
				'4' => '3',
				'5' => '4',
				'6' => '4',
				'7' => '5',
				'8' => '5',
				'9' => '6',
				'10' => '6',
				'11' => '7',
				'12' => '7',
				'13' => '8',
				'14' => '8',
				'15' => '9',
				'16' => '9',
				'17' => '10',
				'18' => '10',
				'19' => '11',
				'20' => '11',
			];

			return $spells_known[$level];

		} elseif ($class == 'Sorcerer') {
			$spells_known = [
				'1' => '2',
				'2' => '3',
				'3' => '4',
				'4' => '5',
				'5' => '6',
				'6' => '7',
				'7' => '8',
				'8' => '9',
				'9' => '10',
				'10' => '11',
				'11' => '12',
				'12' => '12',
				'13' => '13',
				'14' => '13',
				'15' => '14',
				'16' => '14',
				'17' => '15',
				'18' => '15',
				'19' => '15',
				'20' => '15',
			];

			return $spells_known[$level];

		} elseif ($class == 'Warlock') {
			$spells_known = [
				'1' => '2',
				'2' => '3',
				'3' => '4',
				'4' => '5',
				'5' => '6',
				'6' => '7',
				'7' => '8',
				'8' => '9',
				'9' => '10',
				'10' => '10',
				'11' => '11',
				'12' => '11',
				'13' => '12',
				'14' => '12',
				'15' => '13',
				'16' => '13',
				'17' => '14',
				'18' => '14',
				'19' => '15',
				'20' => '15',
			];

			return $spells_known[$level];

		} else {
			return 0;
		}
	}

	public static function checkSpellcaster($class) {
		if (array_key_exists($class, \SpellHelper::getSpellClasses())) {
			return true;
		} else {
			return false;
		}
	}

	public static function checkMultiSpellcaster($classes) {

		$i = 0;

		foreach ($classes as $class) {
			if (array_key_exists($class['name'], \SpellHelper::getSpellClasses())) {
				$i++;
			}
		}

		if ($i > 1) {
			return true;
		} else {
			return false;
		}

	}

	public static function getMultiSpellLevel($classes) {
		$level = 0;
		foreach ($classes as $class) {
			if (array_key_exists($class['name'], \SpellHelper::getSpellClasses())) {
				if ($class['name'] == 'Ranger' || $class['name'] == 'Paladin') {
					$level = $level + floor($class['level'] / 2);
				} elseif ($class['name'] != 'Warlock') {
					$level = $level + $class['level'];
				}
			}
		}

		return $level;

	}

	public static function getSpellSlots($class, $level = 1) {
		if ($level == null) {
			$level = 1;
		}
		

		if ($class == 'Wizard') {
		    $level_table = new LevelTable();
		    
		    # Level 1
		    $level_table->gainSpellSlotAtLevel('0-level (Cantrip)', 1, 3);
		    $level_table->gainSpellSlotAtLevel('1st-level',1, 2);
		    # Level 2
		    $level_table->gainSpellSlotAtLevel('1st-level',2, 1);
		    # Level 3
		    $level_table->gainSpellSlotAtLevel('1st-level',3, 1);
		    $level_table->gainSpellSlotAtLevel('2nd-level',3, 2);
		    # Level 4
		    $level_table->gainSpellSlotAtLevel('0-level (Cantrip)',4, 1);
		    $level_table->gainSpellSlotAtLevel('2nd-level',4, 1);
		    # Level 5
		    $level_table->gainSpellSlotAtLevel('3rd-level',5, 2);
		    # Level 6
		    $level_table->gainSpellSlotAtLevel('3rd-level',6, 1);
		    # Level 7
		    $level_table->gainSpellSlotAtLevel('4th-level',7, 1);
		    # Level 8
		    $level_table->gainSpellSlotAtLevel('4th-level',8, 1);
		    # Level 9
		    $level_table->gainSpellSlotAtLevel('4th-level',9, 1);
		    $level_table->gainSpellSlotAtLevel('5th-level',9, 1);
		    # Level 10
		    $level_table->gainSpellSlotAtLevel('0-level (Cantrip)',10, 1);
		    $level_table->gainSpellSlotAtLevel('5th-level',10, 1);
		    # Level 11
		    $level_table->gainSpellSlotAtLevel('6th-level',11, 1);
		    # Level 12
		    # Level 13
		    $level_table->gainSpellSlotAtLevel('7th-level',13, 1);
		    # Level 14
		    # Level 15
		    $level_table->gainSpellSlotAtLevel('8th-level',15, 1);
		    # Level 16
		    # Level 17
		    $level_table->gainSpellSlotAtLevel('9th-level',17, 1);
		    # Level 18
		    $level_table->gainSpellSlotAtLevel('5th-level',18, 1);
		    # Level 19
		    $level_table->gainSpellSlotAtLevel('6th-level',19, 1);
		    # Level 20
		    $level_table->gainSpellSlotAtLevel('7th-level',20, 1);

			
			return $level_table->index($level);

		} elseif ($class == 'Cleric') {
			$spell_slots = [
				'1' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '2',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'2' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'3' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'4' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'5' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'6' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'7' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'8' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'9' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'10' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'11' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'12' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'13' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'14' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'15' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'16' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'17' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'18' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'19' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'20' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '2',
					'8th-level' => '1',
					'9th-level' => '1',
				],
			];

			if ($level > 20) {
				return $spell_slots['20'];
			}

			return $spell_slots[$level];

		} elseif ($class == 'Druid') {
			$spell_slots = [
				'1' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '2',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'2' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'3' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'4' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'5' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'6' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'7' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'8' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'9' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'10' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'11' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'12' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'13' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'14' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'15' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'16' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'17' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'18' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'19' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'20' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '2',
					'8th-level' => '1',
					'9th-level' => '1',
				],
			];

			if ($level > 20) {
				return $spell_slots['20'];
			}

			return $spell_slots[$level];

		} elseif ($class == 'Ranger') {
			$spell_slots = [
				'1' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'2' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '2',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'3' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'4' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'5' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'6' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'7' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'8' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'9' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'10' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'11' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'12' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'13' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'14' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'15' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'16' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'17' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'18' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'19' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'20' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
			];

			if ($level > 20) {
				return $spell_slots['20'];
			}

			return $spell_slots[$level];

		} elseif ($class == 'Bard') {
			$spell_slots = [
				'1' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '2',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'2' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'3' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'4' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'5' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'6' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'7' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'8' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'9' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'10' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'11' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'12' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'13' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'14' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'15' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'16' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'17' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'18' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'19' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'20' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '2',
					'8th-level' => '1',
					'9th-level' => '1',
				],
			];

			if ($level > 20) {
				return $spell_slots['20'];
			}

			return $spell_slots[$level];

		} elseif ($class == 'Paladin') {
			$spell_slots = [
				'1' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'2' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '2',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'3' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'4' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'5' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'6' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'7' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'8' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'9' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'10' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'11' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'12' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'13' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'14' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'15' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'16' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'17' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'18' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'19' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'20' => [
					'0-level (Cantrip)' => '0',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
			];

			if ($level > 20) {
				return $spell_slots['20'];
			}

			return $spell_slots[$level];

		} elseif ($class == 'Sorcerer') {
			$spell_slots = [
				'1' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '2',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'2' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '3',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'3' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '4',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'4' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'5' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'6' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'7' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '1',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'8' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'9' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '1',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'10' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'11' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'12' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'13' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'14' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'15' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'16' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '0',
				],
				'17' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '2',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'18' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '1',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'19' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '1',
					'8th-level' => '1',
					'9th-level' => '1',
				],
				'20' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '4',
					'2nd-level' => '3',
					'3rd-level' => '3',
					'4th-level' => '3',
					'5th-level' => '3',
					'6th-level' => '2',
					'7th-level' => '2',
					'8th-level' => '1',
					'9th-level' => '1',
				],
			];

			if ($level > 20) {
				return $spell_slots['20'];
			}

			return $spell_slots[$level];

		} elseif ($class == 'Warlock') {
			$spell_slots = [
				'1' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '1',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'2' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '2',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'3' => [
					'0-level (Cantrip)' => '2',
					'1st-level' => '0',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'4' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '0',
					'2nd-level' => '2',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'5' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'6' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '2',
					'4th-level' => '0',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'7' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'8' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '2',
					'5th-level' => '0',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'9' => [
					'0-level (Cantrip)' => '3',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'10' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '2',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'11' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '3',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'12' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '3',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'13' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '3',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'14' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '3',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'15' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '3',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'16' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '3',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'17' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '4',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'18' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '4',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'19' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '4',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
				'20' => [
					'0-level (Cantrip)' => '4',
					'1st-level' => '0',
					'2nd-level' => '0',
					'3rd-level' => '0',
					'4th-level' => '0',
					'5th-level' => '4',
					'6th-level' => '0',
					'7th-level' => '0',
					'8th-level' => '0',
					'9th-level' => '0',
				],
			];

			if ($level > 20) {
				return $spell_slots['20'];
			}

			return $spell_slots[$level];

		} else {
			return "None";
		}
	}
	
	public static function getMultiSpellSlots($level = 1) {
		if ($level == null) {
			$level = 1;
		}

		$spell_slots = [
			'1' => [
				'1st-level' => '2',
				'2nd-level' => '0',
				'3rd-level' => '0',
				'4th-level' => '0',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'2' => [
				'1st-level' => '3',
				'2nd-level' => '0',
				'3rd-level' => '0',
				'4th-level' => '0',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'3' => [
				'1st-level' => '4',
				'2nd-level' => '2',
				'3rd-level' => '0',
				'4th-level' => '0',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'4' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '0',
				'4th-level' => '0',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'5' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '2',
				'4th-level' => '0',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'6' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '0',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'7' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '1',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'8' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '2',
				'5th-level' => '0',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'9' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '1',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'10' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '0',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'11' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '1',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'12' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '1',
				'7th-level' => '0',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'13' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '1',
				'7th-level' => '1',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'14' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '1',
				'7th-level' => '1',
				'8th-level' => '0',
				'9th-level' => '0',
			],
			'15' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '1',
				'7th-level' => '1',
				'8th-level' => '1',
				'9th-level' => '0',
			],
			'16' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '1',
				'7th-level' => '1',
				'8th-level' => '1',
				'9th-level' => '0',
			],
			'17' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '2',
				'6th-level' => '1',
				'7th-level' => '1',
				'8th-level' => '1',
				'9th-level' => '1',
			],
			'18' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '3',
				'6th-level' => '1',
				'7th-level' => '1',
				'8th-level' => '1',
				'9th-level' => '1',
			],
			'19' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '3',
				'6th-level' => '2',
				'7th-level' => '1',
				'8th-level' => '1',
				'9th-level' => '1',
			],
			'20' => [
				'1st-level' => '4',
				'2nd-level' => '3',
				'3rd-level' => '3',
				'4th-level' => '3',
				'5th-level' => '3',
				'6th-level' => '2',
				'7th-level' => '2',
				'8th-level' => '1',
				'9th-level' => '1',
			],
		];

		if ($level > 20) {
			return $spell_slots['20'];
		}

		return $spell_slots[$level];
	}

	public static function getRedditMarkdown($spell) {
		$components = $spell->components . " " . ($spell->material ? '(' . $spell->material . ')' : '');

		$output = "#### [" . $spell->name . "](" . url('spell', $spell->id) . ")
		*" . ucfirst($spell->level) . ", " . $spell->school . "*";

		$output .= "
		&#010;
		**Casting Time:** " . $spell->casting_time . "&#010;
		**Range:** " . $spell->range . "&#010;
		**Components:** " . $components . "&#010;
		**Duration:** " . $spell->duration . "&#010;&#010;";

		$output .= Common::redditDescription($spell->description);

		if ($spell->higher_level) {
			$output .= "&#010;&#010;**At Higher Levels** &#010;&#010;" . $spell->higher_level;
		}

		return str_replace("\t", '', $output);

	}

	public static function getHomebreweryMarkdown($spell) {
		$components = $spell->components . " " . ($spell->material ? '(' . $spell->material . ')' : '');

		$output = "#### " . $spell->name . "
		*" . ucfirst($spell->level) . ", " . $spell->school . "*";

		$output .= "
		___
		- **Casting Time:** " . $spell->casting_time . "
		- **Range:** " . $spell->range . "
		- **Components:** " . $components . "
		- **Duration:** " . $spell->duration . "\r\r\n";

		$output .= Common::redditDescription($spell->description);

		if ($spell->higher_level) {
			$output .= "\r\r\n___ \r\r\n- **At Higher Levels** " . $spell->higher_level;
		}

		return str_replace("\t", '', $output);
	}

}
