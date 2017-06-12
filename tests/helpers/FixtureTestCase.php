<?php

class FixtureTestCase extends PHPUnit_Extensions_Database_TestCase {
	
	private $conn = null;
	
	public function getConnection() {
		if ($this->conn === null) {
			try {
				$pdo = new PDO('mysql:host=localhost;dbname=sportgame_test', 'root', 'root');
				$this->conn = $this->createDefaultDBConnection($pdo, 'sportgame_test');
			} catch(PDOEXCEPTION $e) {
				echo $e->getMessage();
			}
		}
		return $this->$conn;
	}
	
	public function setUp() {
		$conn = $this->getConnection();
		$pdo = $conn->getConnection();
	}
	
	public function tearDown() {
	}
	
	public function getDataSet() {
	}
	
	public function loadDataSet() {
	}
}