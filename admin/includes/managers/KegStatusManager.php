<?php
namespace RaspberryPints\Admin\Managers;

use RaspberryPints\DB;
use RaspberryPints\Admin\Models\KegStatus;

class KegStatusManager{

	function GetAll(){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM kegStatuses ORDER BY name";
		$result = $DB->get($sql);

		$kegStatuses = array();
		foreach($result as $i => $row){
			$kegStatus = new KegStatus();
			$kegStatus->setFromArray($row);
			$kegStatuses[$kegStatus->get_code()] = $kegStatus;
		}

		return $kegStatuses;
	}

	function GetByCode($code){
		$DB = DB:getInstance();
		$sql = "SELECT * FROM kegStatuses WHERE code = ?";
		$result = $DB->get($sql, [
			['type' => DB:BIND_TYPE_STRING, 'value' => $code]
		]);


		if(count($result) == 1)
			$kegStatus = new KegStatus();
			$kegStatus->setFromArray($result[0]);
			return $kegStatus;
		}

		return null;
	}

}
