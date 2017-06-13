<?php
// vendor/autoload is loaded by the phpunit command line
//	--bootstrap

use PHPUnit\Framework\TestCase;
use \Paooolino\Sportgame;

class_alias('\RedBeanPHP\R','\R');
R::setup('mysql:host=localhost;dbname=sportgame_test', 'root', 'root');

class AppTest extends TestCase {
	
	protected function setUp() {
		$sg = new Sportgame();
		$sg->initDbTableFromCsv(dirname(__FILE__) . '/helpers/', 'options');
	}
	
	protected function tearDown() {
		R::nuke();
	}
	
	public function testPassTurn() {
		$sg = new Sportgame();
		
		$turnBefore = $sg->getOption("current_turn");
		$sg->passTurn(1);
		$turnAfter = $sg->getOption("current_turn");
		
		$this->assertEquals(0, $turnBefore, "initial turn value");
		$this->assertEquals(1, $turnAfter, "turn value after 1 pass");
	}
	
	public function testUpdatePlayer() {
		//
	}

}
