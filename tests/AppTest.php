<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;

class AppTest extends TestCase {
	
	/**
	 * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	public function getConnection() {
		$pdo = new PDO('sqlite::memory:');
		return $this->createDefaultDBConnection($pdo, ':memory:');
	}
	
	/**
	 * @return PHPUnit_Extensions_Database_DataSet_IDataSet
	 */
	public function getDataSet() {
		return $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/guestbook-seed.xml');
	}
	
}