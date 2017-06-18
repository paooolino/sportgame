<?php
namespace Paooolino;

class SportGame {
	
	public function checkdDatabaseIntegrity() {
		
	}
	
}

class Sportgame_old {

	private $db_type = ""; // mysql | sqlite
	
	public function __construct($db_type = "mysql") {
		$this->db_type = $db_type;
	}
	
	public function initDbTableFromCsv($filepath, $filename) {
		// read the first two lines of the file
		$file = fopen($filepath . $filename . ".csv", 'r');
		
		// the first containing field names
		$fields = [];
		// the second containing the first record
		$record = [];
		$line = fgetcsv($file);
		if ($line !== FALSE) {
			$fields = $line;
		}
		$line = fgetcsv($file);
		if ($line !== FALSE) {
			$record = $line; 
		}
		
		fclose($file);
	
		// create a bean with the first record and field names, stores it creating table schema.
		$line = \R::dispense($filename);
		$count = 0;
		foreach ($fields as $field) {
			$line->$field = $record[$count];
			$count++;
		}
		\R::store($line);
		
		// bulk insert the other records
		if ($this->db_type == "mysql") {
			$query = "
				LOAD DATA LOCAL INFILE '" . __DIR__ . "/../" . $filepath . $filename . ".csv' 
				INTO TABLE `". $filename ."` 
				FIELDS TERMINATED BY ',' 
				OPTIONALLY ENCLOSED BY '\"'
				LINES TERMINATED BY '\r\n'
				IGNORE 2 LINES
			";
		}

		if ($this->db_type == "sqlite") {
			// to do
			//	ex. https://stackoverflow.com/questions/14947916/import-csv-to-sqlite
		}
		
		\R::exec($query);
		
		/*
		$file = fopen($filepath . $filename . ".csv", 'r');
		
		$fields = [];
		$records = [];
		while (($line = fgetcsv($file)) !== FALSE) {
			if (empty($fields)) {
				$fields = $line;
			} else {
				array_push($records, $line);
			}
		}
		fclose($file);

		foreach ($records as $record) {
			$line = \R::dispense($filename);
			$count = 0;
			foreach ($fields as $field) {
				$line->$field = $record[$count];
				$count++;
			}
			\R::store($line);
		}
		*/
	}
	
	public function initPlayers() {
		$teams = \R::findAll('team');
		foreach ($teams as $team) {
			$roles = "PPPDDDDDDDDMMMMMMMAAAAAAA";
			for ($i = 0; $i < strlen($roles); $i++) {
				$player = \R::dispense("player");
				$player->role = $roles[$i];
				$player->country = "ITA";
				$player->name = $this->getRandomName("ITA");
				$player->surname = $this->getRandomSurname("ITA");
				$player->age = rand(16,38);
				$player->quality = rand((10 - ($team->livello)) * 5, 100);
				$player->form = rand(0,100);
				$player->team = $team;
				\R::store($player);
			}
		}
	}
	
	public function initCalendar() {
		$fixtures = [
			[[1,10],[2,9],[3,8],[4,7],[5,6]],
			[[10,6],[7,5],[8,4],[9,3],[1,2]],
			[[2,10],[3,1],[4,9],[5,8],[6,7]],
			[[10,7],[8,6],[9,5],[1,4],[2,3]],
			[[3,10],[4,2],[5,1],[6,9],[7,8]],
			[[10,8],[9,7],[1,6],[2,5],[3,4]],
			[[4,10],[5,3],[6,2],[7,1],[8,9]],
			[[10,9],[1,8],[2,7],[3,6],[4,5]],
			[[5,10],[6,4],[7,3],[8,2],[9,1]]
		];
		
		$leagues = \R::findAll('league');
		foreach ($leagues as $league) {
			$teams = \R::find('team', ' WHERE livello = ?', [$league->livello]); 
			$teams = array_values($teams);
			for ($i = 0; $i < count($fixtures); $i++) {
				for ($j = 0; $j < count($fixtures[$i]); $j++) {
					$match = \R::dispense("match");
					$match->round = $i;
					$match->league = $league;
					$match->team1 = $teams[$fixtures[$i][$j][0]-1];
					$match->team2 = $teams[$fixtures[$i][$j][1]-1];
					$match->turn = ($i + 1) * 7;
					$match->result1goal1 = 0;
					$match->result1goal2 = 0;
					$match->result2goal1 = 0;
					$match->result2goal2 = 0;
					\R::store($match);
				}
			}
		}
	}
	
	public function getOption($option_name) {
		$option = \R::findOne('option', ' WHERE option_name = ?', [$option_name]);
		return $option->option_value;
	}
	
	public function setOption($option_name, $option_value) {
		$option = \R::findOne('option', ' WHERE option_name = ?', [$option_name]);
		$option->option_value = $option_value;
		\R::store($option);
	}
	
	public function passTurn($n) {
		$current_turn = $this->getOption("current_turn");
		$this->setOption("current_turn", $current_turn + $n);
	}
	
	public function updatePlayers() {
		$players = \R::findAll("player");
		foreach ($players as $player) {
			$var = rand(0,2) - 1;
			if ($var != 0) {
				$player->quality = $player->quality + $var;
				\R::store($player);
				
				$variation = \R::dispense("playervariation");
				$variation->player = $player;
				$variation->value = $var;
				$variation->turn = $this->getOption("current_turn");
				\R::store($variation);
			}
		}
	}
	
	private function getRandomName($country) {
		//var_dump($this->db_type);die();
		if ($this->db_type == "mysql") {
			$name = \R::findOne('name', ' ORDER BY RAND() LIMIT 1');
		}
		if ($this->db_type == "sqlite") {
			$name = \R::findOne('name', ' ORDER BY RANDOM() LIMIT 1');
		}
		return $name->name;
	}
	
	private function getRandomSurname($country) {
		if ($this->db_type == "mysql") {
			$surname = \R::findOne('surname', ' ORDER BY RAND() LIMIT 1');
		}
		if ($this->db_type == "sqlite") {
			$surname = \R::findOne('surname', ' ORDER BY RANDOM() LIMIT 1');
		}		
		return $surname->surname;
	}
	
}
