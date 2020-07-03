<?php
namespace RaspberryPints\Admin\Managers;

use RaspberryPints\DB;
use RaspberryPints\Admin\Models\BeerStyleGuideline;

class BeerStyleGuidelineManager{

	function GetAll(){
		$DB = DB::getInstance();
		$sql = "SELECT * FROM beerStyleGuidelines ORDER BY id asc";
		$result = $DB->get($sql);

		$beerStyles = array();
		foreach($result as $i => $row){
			$beerStyle = new BeerStyleGuideline();
			$beerStyle->setFromArray($row);
			$beerStyles[$beerStyle->get_id()] = $beerStyle;
		}

		return $beerStyles;
	}

	function GetById($id){
		$DB = DB::getInstance();
		$sql = "SELECT * FROM beerStyleGuidelines WHERE id = $id";
		$result = $DB->get($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $id]
		]);

		if(count($result) == 1) {
			$beerStyle = new BeerStyleGuideline();
			$beerStyle->setFromArray($result[0]);
			return $beerStyle;
		}

		return null;
	}
}
