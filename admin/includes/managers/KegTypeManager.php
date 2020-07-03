<?php
namespace RaspberryPints\Admin\Managers;

use RaspberryPints\DB;
use RaspberryPints\Admin\Models\KegType;

class KegTypeManager{

	function GetAll(){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM kegTypes ORDER BY displayName";
		$result = $DB->get($sql);

		$kegTypes = array();
		foreach($result as $i => $row){
			$kegType = new KegType();
			$kegType->setFromArray($row);
			$kegTypes[$kegType->get_id()] = $kegType;
		}

		return $kegTypes;
	}

	function GetById($id){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM kegTypes WHERE id = ?";
		$result = $DB->get($sql, [
			['type' => DB:BIND_TYPE_STRING, 'value' => $id]
		]);

		if(count($result) == 1)
			$kegType = new KegType();
			$kegType->setFromArray($result[0]);
			return $kegType;
		}

		return null;
	}
}
