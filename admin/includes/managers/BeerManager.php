<?php
namespace RaspberryPints\Admin\Managers;

use RaspberryPints\Admin\Models\Beer;
use RaspberryPints\DB;

class BeerManager{

	function Save($beer){
		$DB = DB:getInstance();

		if($beer->get_id()) {
			$sql = 	"UPDATE beers " .
					"SET " .
						"name = ?" .
						"beerStyleId = ?, " .
						"notes = ?, " .
						"ogEst = ?, " .
						"fgEst = ?, " .
						"srmEst = ?, " .
						"ibuEst = ?, " .
						"modifiedDate = NOW() ".
					"WHERE id = ?";
			$DB->execute($sql, [
				['type' => DB::BIND_TYPE_STRING, 'value' => encode($beer->get_name())],
				['type' => DB::BIND_TYPE_INT, 'value' => $beer->get_beerStyleId()],
				['type' => DB::BIND_TYPE_STRING, 'value' => encode($beer->get_notes())],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_og()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_fg()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_srm()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_ibu()],
				['type' => DB::BIND_TYPE_INT, 'value' => $beer->get_id()]
			]);
		}
		else{
			$sql = 	"INSERT INTO beers(name, beerStyleId, notes, ogEst, fgEst, srmEst, ibuEst, createdDate, modifiedDate ) " .
					"VALUES(?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
			$DB->execute($sql, [
				['type' => DB::BIND_TYPE_STRING, 'value' => encode($beer->get_name())],
				['type' => DB::BIND_TYPE_INT, 'value' => $beer->get_beerStyleId()],
				['type' => DB::BIND_TYPE_STRING, 'value' => encode($beer->get_notes())],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_og()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_fg()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_srm()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $beer->get_ibu()]
			]);
		}
	}

	function GetAll(){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM beers ORDER BY name";
		$result = $DB->get($sql);

		$beers = array();
		foreach($result as $i => $row){
			$beer = new Beer();
			$beer->setFromArray($row);
			$beers[$beer->get_id()] = $beer;
		}

		return $beers;
	}

	function GetAllActive(){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM beers WHERE active = 1 ORDER BY name";
		$result = $DB->get($sql);

		$beers = array();
		foreach($result as $i => $row){
			$beer = new Beer();
			$beer->setFromArray($row);
			$beers[$beer->get_id()] = $beer;
		}

		return $beers;
	}

	function GetById($id){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM beers WHERE id = ?";
		$result = $DB->get($sql, [
			['type' => DB:BIND_TYPE_INT, 'value' => $id]
		]);

		if(count($result) == 1)
			$beer = new Beer();
			$beer->setFromArray($result[0]);
			return $beer;
		}

		return null;
	}

	function Inactivate($id){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM taps WHERE beerId = ? AND active = 1";
		$result = $DB->get($sql, [
			['type' => DB:BIND_TYPE_INT, 'value' => $id]
		]);

		if(count($result) > 0 ){
			$_SESSION['errorMessage'] = "Beer is associated with an active tap and could not be deleted.";
			return;
		}

		$sql = "UPDATE beers SET active = 0 WHERE id = ?";
		$DB->execute($sql, [
			['type' => DB:BIND_TYPE_INT, 'value' => $id]
		]);

		$_SESSION['successMessage'] = "Beer successfully deleted.";
	}
}
