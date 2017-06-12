<?php
use \Paooolino\Sportgame;

//class_alias('\RedBeanPHP\R','\R');
//R::setup($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

class AppTest extends PHPUnit_Extensions_Database_TestCase {
	
	protected function setUp() {
		//R::nuke();
	}
	
	public function testPassTurn() {
		$sg = new Sportgame();
		$sg->passTurn();
	}
	
	public function testUpdatePlayer() {
	}
	
	/**
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	public function getConnection()	{
		$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
		return $this->createDefaultDBConnection($pdo, $GLOBALS['DB_DBNAME']);
	}

	/**
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet() {
		$dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
		$dataSet->addTable('teams', dirname(__FILE__) . "/dbdata/teams.csv");
		return $dataSet;
	}
}
