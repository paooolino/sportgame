<?php
// vendor/autoload is loaded by the phpunit command line
//	--bootstrap

use PHPUnit\Framework\TestCase;
use \Paooolino\Sportgame;

class_alias('\RedBeanPHP\R','\R');
R::setup('mysql:host=localhost;dbname=sportgame_test', 'root', 'root');

class AppTest extends TestCase {
	
	protected function setUp() {
		R::nuke();
		$sg = new Sportgame();
		$sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'options');
		$sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'names');
		$sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'surnames');
		$sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'leagues');
		$sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'teams');
	}
	
	protected function tearDown() {
		R::nuke();
	}
	
	public function testUpdatePlayers() {
		$sg = new SportGame();
		$sg->initPlayers();
		$sg->updatePlayers();
		
		$players = R::findAll("players");
		$variation = R::findOne("playervariations");
		echo $variation;echo "---";
		if ($variation) {
			$var = $variation->value;
			$player = $variation->fetchAs('players')->player->id;
			
			echo $player;
			print_r($player);die();
			$expected = $players[$player->id]->quality + $var;
			$this->assertEquals($expected, $player->quality, "player quality has been updated and variation recorded");
		}
	}
	
	public function testInitCalendar() {
		$sg = new SportGame();
		$sg->initCalendar();
		$leagues = R::findAll('leagues');
		$matches = R::findAll('matches');
		$matches_per_league = 5 * 9;
		$this->assertEquals(count($leagues) * $matches_per_league, count($matches), "every league has 45 matches");
	}
	
	public function testPassTurn() {
		$sg = new Sportgame();
		
		$turnBefore = $sg->getOption("current_turn");
		$sg->passTurn(1);
		$turnAfter = $sg->getOption("current_turn");
		
		$this->assertEquals(0, $turnBefore, "initial turn value");
		$this->assertEquals(1, $turnAfter, "turn value after 1 pass");
	}
	
	public function testInitPlayers() {
		$sg = new SportGame();
		
		$teams = R::findAll('teams');
		$sg->initPlayers();
		$players = R::findAll('players');
		
		$this->assertEquals(25 * count($teams), count($players), "every team has 25 players");
	}
	
	public function testUpdatePlayer() {
		//
	}

}
