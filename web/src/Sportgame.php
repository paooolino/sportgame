<?php
namespace Paooolino;

class Sportgame {

	public function initDbTableFromCsv($filepath, $filename) {
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
		
		\R::wipe($filename);

		foreach ($records as $record) {
			$line = \R::dispense($filename);
			$count = 0;
			foreach ($fields as $field) {
				$line->$field = $record[$count];
				$count++;
			}
			\R::store($line);
		}
	}
	
}
