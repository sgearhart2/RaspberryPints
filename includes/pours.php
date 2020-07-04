<?php
use RapsberryPints\ConfigNames;
use RaspberryPinhts\DB;

//Unused at the moment will call untappdPHP library to post to Untappd
//include __DIR__."/app/library/UntappdPHP/lib/untappdPHP.php";

//This can be used to choose between CSV or MYSQL DB
	$db = true;


if($db){
		// Connect to the database
		db();


		$config = array();
		//Pulls config information (not currently used)
		$sql = "SELECT * FROM config";
		$DB = DB::getInstance();
		$results = $DB->get($sql);
		foreach($results as $row) {
			$config[$row['configName']] = $row['configValue'];
		}

		// Creates arguments from info passed by python script from Flow Meters
		$PIN = $argv[1];
		$PULSE_COUNT = $argv[2];

		//Unused SQL call at the moment
		//$sql = "select tapIndex,batchId,PulsesPerLiter from taps where pinAddress = $PIN";

		// SQL call to get corresponding tapID to pinId.
		$sql = "select id from taps where pinId = ? and active = '1'";
		$taps = $DB->get($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $PIN ]
		]);
		//$amount = $PULSE_COUNT / 165;

		// Sets the amount to be a fraction of a gallon based on 165 ounces per pulse
		$amount = $PULSE_COUNT / 21120;
		 if (count($taps) == 0) {
                echo "No Active Taps";
     } else {

		//Unused Query at the moment, based on future table
		//$qry = "INSERT INTO pours(tapId,amountPoured,batchId,pinAddress,pulseCount,pulsesPerLiter,liters) values ('".$taps[0]."','".$amount."','".$taps[1]."','".$PIN."','".$PULSE_COUNT."','".$taps[2]."','".$PULSE_COUNT / $taps[2]."')";

		// Inserts in to the pours table
		$qry = "INSERT INTO pours(tapId, pinId, amountPoured, pulses) values (?,?,?,?)";
		$DB->execute($sql, [
			['type' => DB::BIND_TYPE_INT, 'value' => $taps[0]['id']],
			['type' => DB::BIND_TYPE_INT, 'value' => $PIN],
			['type' => DB::BIND_TYPE_DOUBLE, 'value' => $amount],
			['type' => DB::BIND_TYPE_INT 'value' => $PULSE_COUNT]
		]);


}

}

		// REFRESHES CHROMIUM BROWSER ON LOCAL HOST ONLY
		// COMMENT OUT TO DISABLE
		exec(__DIR__."/refresh.sh");

?>
