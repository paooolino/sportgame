<?php
// vendor/autoload is loaded by the phpunit command line
//	--bootstrap

use PHPUnit\Framework\TestCase;
use \Paooolino\Sportgame;

class_alias('\RedBeanPHP\R','\R');
//R::setup('mysql:host=localhost;dbname=sportgame_test', 'root', 'root');
R::setup('sqlite::memory:');

class AppTest extends TestCase {
	
	private $sg;
	
	/**
	 *	inizializza un database con 2 leagues, 20 teams
	 */
	protected function setUp() {
		R::nuke();
		$this->sg = new Sportgame("sqlite");
		$this->sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'option');
		$this->sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'name');
		$this->sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'surname');
		$this->sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'league');
		$this->sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'team');
	}
	
	protected function tearDown() {
		R::nuke();
	}

	/**
	 *	
	 */
	public function testUpdatePlayers() {
		$this->sg->initPlayers();
		$players = R::findAll("player");
		
		$this->sg->updatePlayers();
		$variation = R::findOne("playervariation");

		if ($variation) {
			$var = $variation->value;
			$player = $variation->player;
			$expected = $players[$player->id]->quality + $var;
			$this->assertEquals($expected, $player->quality, "player quality has been updated and variation recorded");
		}
	}
	
	public function testInitCalendar() {
		$this->sg->initCalendar();
		$leagues = R::findAll('league');
		$matches = R::findAll('match');
		$matches_per_league = 5 * 9;
		$this->assertEquals(count($leagues) * $matches_per_league, count($matches), "every league has 45 matches");
	}
	
	public function testPassTurn() {
		$turnBefore = $this->sg->getOption("current_turn");
		$this->sg->passTurn(1);
		$turnAfter = $this->sg->getOption("current_turn");
		
		$this->assertEquals(0, $turnBefore, "initial turn value");
		$this->assertEquals(1, $turnAfter, "turn value after 1 pass");
	}
	
	public function testInitPlayers() {
		$teams = R::findAll('team');
		$this->sg->initPlayers();
		$players = R::findAll('player');
		
		$this->assertEquals(25 * count($teams), count($players), "every team has 25 players");
	}

}
