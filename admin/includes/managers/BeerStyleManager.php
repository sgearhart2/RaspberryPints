<?php
namespace RaspberryPints\Admin\Managers;

use RaspberryPints\DB;
use RaspberryPints\Admin\Models\BeerStyle;

class BeerStyleManager{

	function GetAll(){
		$sql="SELECT * FROM beerStyles ORDER BY name";
		$qry = mysql_query($sql);

		$beerStyles = array();
		while($i = mysql_fetch_array($qry)){
			$beerStyle = new beerStyle();
			$beerStyle->setFromArray($i);
			$beerStyles[$beerStyle->get_id()] = $beerStyle;
		}

		return $beerStyles;
	}



	function GetById($id){
		$sql="SELECT * FROM beerStyles WHERE id = $id";
		$qry = mysql_query($sql);

		if( $i = mysql_fetch_array($qry) ){
			$beerStyle = new beerStyle();
			$beerStyle->setFromArray($i);
			return $beerStyle;
		}

		return null;
	}
}
