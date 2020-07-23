<?php
namespace RaspberryPints\Admin\Managers;

use RaspberryPints\ConfigNames;
use RaspberryPints\DB;
use RaspberryPints\Admin\Models\Tap;

class TapManager{

	function Save(Tap $tap) {
		$DB = DB::getInstance();

		$sql = "UPDATE kegs k SET k.kegStatusCode = 'SERVING', modifiedDate = NOW() where id = ?;";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_kegId()]
		]);

		$sql = "UPDATE taps SET active = 0, modifiedDate = NOW() WHERE active = 1 AND tapNumber = ?;";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_tapNumber()]
		]);

		if($tap->get_id()){
			$sql = 	"UPDATE taps SET beerId = ?, kegId = ?, tapNumber = ?, pinId = ?, ogAct = ?, fgAct = ?, srmAct = ?, ibuAct = ?, startAmount = ?, active = ?, modifiedDate = NOW() WHERE id = ?;";

			$DB->execute($sql, [
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_beerId()],
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_kegId()],
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_tapNumber()],
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_pinId()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_og()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_fg()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_srm()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_ibu()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_startAmount()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $tap->get_active()],
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_id()]
			]);
		}else{
			$sql = 	"INSERT INTO taps (beerId, kegId, tapNumber, pinId, ogAct, fgAct, srmAct, ibuAct, startAmount, currentAmount, active, createdDate, modifiedDate ) " .
					"VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());";

			$DB->execute($sql, [
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_beerId()],
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_kegId()],
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_tapNumber()],
				['type' => DB::BIND_TYPE_INT, 'value' => $tap->get_pinId()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_og()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_fg()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_srm()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_ibu()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_startAmount()],
				['type' => DB::BIND_TYPE_DOUBLE, 'value' => $tap->get_startAmount()],
				['type' => DB::BIND_TYPE_STRING, 'value' => $tap->get_active()]
			]);
		}
	}

	function GetById($id){
		$id = (int) preg_replace('/\D/', '', $id);

		$DB = DB::getInstance();

		$sql = "SELECT * FROM taps WHERE id = ?";
		$result = $DB->get($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $id]
		]);

		if( count($result) > 0){
			$tap = new Tap();
			$tap->setFromArray($result[0]);
			return $tap;
		}

		return null;
	}

	function updateTapNumber($newTapNumber){
		$DB = DB::getInstance();

		$sql = "UPDATE config SET configValue = ?, modifiedDate = NOW() WHERE configName = ?;";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $newTapNumber],
			['type' => DB::BIND_TYPE_STRING, 'value' => ConfigNames::NumberOfTaps]
		]);

		$sql = "UPDATE kegs SET kegStatusCode = 'SANITIZED', modifiedDate = NOW() WHERE id in (SELECT kegId from taps where tapNumber > ? AND active = 1)";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $newTapNumber],
		]);

		$sql = "UPDATE taps SET active = 0, modifiedDate = NOW() WHERE active = 1 AND tapNumber > ?;";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $newTapNumber]
		]);
	}

	function getTapNumber(){
		$DB = DB::getInstance();

		$sql = "SELECT configValue FROM config WHERE configName = ?;";

		$result = $DB->get($sql, [
			['type' => DB::BIND_TYPE_STRING, 'value' => ConfigNames::NumberOfTaps]
		]);

		if( count($result) > 0 ){
			return $result[0]['configValue'];
		}
	}

	function getActiveTaps(){
		$DB = DB::getInstance();

		$sql=  "SELECT * FROM taps WHERE active = 1";
		$result = $DB->get($sql);

		$taps = array();
		foreach($result as $i => $row) {
			$tap = new Tap();
			$tap->setFromArray($row);
			$taps[$tap->get_tapNumber()] = $tap;
		}

		return $taps;
	}

	function closeTap($id){
		$DB = DB::getInstance();

		$sql = "UPDATE taps SET active = 0, modifiedDate = NOW() WHERE id = ?";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $id]
		]);

		$sql = "UPDATE kegs k, taps t SET k.kegStatusCode = 'NEEDS_CLEANING' WHERE t.kegId = k.id AND t.Id = ?";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $id]
		]);
	}
}
