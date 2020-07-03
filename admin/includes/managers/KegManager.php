<?php
namespace RaspberryPints\Admin\Managers;


use RaspberryPints\DB;
use RaspberryPints\Admin\Models\Keg;

class KegManager{

	function GetAll(){
		$DB = DB::getInstance();
		$sql = "SELECT * FROM kegs ORDER BY label";
		$result = $DB->get($sql);

		$kegs = array();
		foreach($result as $i => $row){
			$keg = new Keg();
			$keg->setFromArray($row);
			$kegs[$keg->get_id()] = $keg;
		}

		return $kegs;
	}

	function GetAllActive(){
		$DB = DB::getInstance();
		$sql = "SELECT * FROM kegs WHERE active = 1 ORDER BY label";
		$result = $DB->get($sql);

		$kegs = array();
		foreach($result as $i => $row){
			$keg = new Keg();
			$keg->setFromArray($row);
			$kegs[$keg->get_id()] = $keg;
		}

		return $kegs;
	}

	function GetAllAvailable(){
		$DB = DB::getInstance();

		$sql = "SELECT * FROM kegs WHERE active = 1
			AND kegStatusCode NOT IN (
				'SERVING',
				'NEEDS_CLEANING',
				'NEEDS_PARTS',
				'NEEDS_REPAIRS'
			)
		ORDER BY label";
		$result = $DB->get($sql);

		$kegs = array();
		foreach($result as $i => $row){
			$keg = new Keg();
			$keg->setFromArray($row);
			$kegs[$keg->get_id()] = $keg;
		}

		return $kegs;
	}

	function GetById($id){
		$DB = DB::getInstance();
		$sql = "SELECT * FROM kegs WHERE id = ?";
		$result = $DB->get($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $id]
		]);

		if(count($result) == 1){
			$keg = new Keg();
			$keg->setFromArray($result[0]);
			return $keg;
		}

		return null;
	}


	function Save($keg){
		$DB = DB::getInstance();
		$sql = "";
		if($keg->get_id()){
			$sql = 	"UPDATE kegs " .
					"SET " .
						"label = ?, " .
						"kegTypeId = ?, " .
						"make = ?, " .
						"model = ?, " .
						"serial = ?, " .
						"stampedOwner = ?, " .
						"stampedLoc = ?, " .
						"weight = ?, " .
						"notes = ?, " .
						"kegStatusCode = ?, " .
						"modifiedDate = NOW() ".
					"WHERE id = ?";
			$DB->execute($sql, [
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_label()],
				['type' => DB::BIND_TYPE_INT, 'value' => $keg->get_kegTypeId()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_make()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_model()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_serial()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_stampedOwner()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_stampedLoc()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $keg->get_weight()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_notes()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_kegStatusCode()],
				['type' => DB::BIND_TYPE_INT, 'value' => $keg->get_id()]
			]);
		}
		else
		{
			$sql = 	"INSERT INTO kegs(label, kegTypeId, make, model, serial, stampedOwner, stampedLoc, weight, notes, kegStatusCode, createdDate, modifiedDate ) " .
					"VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
				$DB->execute($sql, [
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_label()],
					['type' => DB::BIND_TYPE_INT, 'value' => $keg->get_kegTypeId()],
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_make()],
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_model()],
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_serial()],
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_stampedOwner()],
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_stampedLoc()],
					['type' => DB::BIND_TYPE_DOUBLE, 'value' => $keg->get_weight()],
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_notes()],
					['type' => DB::BIND_TYPE_STRING, 'value' => $keg->get_kegStatusCode()]
				]);
		}
	}

	function Inactivate($id){
		$DB = DB::getInstance();
		$sql = "SELECT * FROM taps WHERE kegId = ? AND active = 1";
		$result = $DB->get($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $id]
		]);

		if( count($result) > 0 ){
			$_SESSION['errorMessage'] = "Keg is associated with an active tap and could not be deleted.";
			return;
		}

		$sql = "UPDATE kegs SET active = 0 WHERE id = ?";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $id]
		]);

		$_SESSION['successMessage'] = "Keg successfully deleted.";
	}
}
