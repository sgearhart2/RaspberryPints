<?php
	
	require_once __DIR__.'/includes/config.php';
	db();
	
	$configName='horizontalDisplay';
	$configValue=1;
	$displayName='Display Horizontal (2 columns)';
	$showOnPanel = 1;
	$currentdate = Date('Y-m-d H:i:s');
	$sql = "Select count(*) as num from config where configName = '$configName'";
	$qry = mysql_query($sql);
	
	$result = mysql_fetch_array($qry);
	if(! ($result[0] > 0)) {
		$sql = "INSERT INTO config (configName, configValue, displayName, showOnPanel, createdDate, modifiedDate) VALUES ('" . $configName . "','" . $configValue . "','" . $displayName . "','" . $showOnPanel . "','" . $currentdate . "','" . $currentdate . "');";
		$result = mysql_query($sql);
		echo 'Created config value "' . $configName . '".';
	}
	else {
		echo 'Config value "' . $configName . '" already exists.';
	}
	mysql_close();
?>