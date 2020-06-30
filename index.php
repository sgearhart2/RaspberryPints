<?php
	if (!file_exists(__DIR__.'/includes/config.php')) {
		header('Location: install/index.php', true, 303);
		die();
	}
?>
<?php
	require_once __DIR__.'/includes/config_names.php';

	require_once __DIR__.'/includes/config.php';

	require_once __DIR__.'/admin/includes/managers/tap_manager.php';
	
	//This can be used to choose between CSV or MYSQL DB
	$db = true;
	
	// Setup array for all the beers that will be contained in the list
	$beers = array();
	
	if($db){
		// Connect to the database
		db();
		
		$config = array();
		$sql = "SELECT * FROM config";
		$qry = mysql_query($sql);
		while($c = mysql_fetch_array($qry)){
			$config[$c['configName']] = $c['configValue'];
		}
		
		$sql =  "SELECT * FROM vwGetActiveTaps";
		$qry = mysql_query($sql);
		while($b = mysql_fetch_array($qry))
		{
			$beeritem = array(
				"id" => $b['id'],
				"beername" => $b['name'],
				"style" => $b['style'],
				"notes" => $b['notes'],
				"og" => $b['ogAct'],
				"fg" => $b['fgAct'],
				"srm" => $b['srmAct'],
				"ibu" => $b['ibuAct'],
				"startAmount" => $b['startAmount'],
				"amountPoured" => $b['amountPoured'],
				"remainAmount" => $b['remainAmount'],
				"tapNumber" => $b['tapNumber'],
				"srmRgb" => $b['srmRgb']
			);
			$beers[$b['tapNumber']] = $beeritem;
		}
		
		$tapManager = new TapManager();
		$numberOfTaps = $tapManager->GetTapNumber();
	}
	
	function outputHeaders() {
		global $config;
		$output = '';
		
		if($config[ConfigNames::ShowTapNumCol]){ 
			$output .= '
						<div class="tap-num cell">
							TAP<BR>#
						</div>';
		}
		if($config[ConfigNames::ShowSrmCol]){ 
			$output .= '
						<div class="srm cell">
							GRAVITY<hr>COLOR
						</div>';
		}
		if($config[ConfigNames::ShowIbuCol]){ 
			$output .= '
						<div class="ibu cell">
							BALANCE<hr>BITTERNESS
						</div>';
		}
		$output .= '
						<div class="name cell">
							BEER NAME &nbsp; & &nbsp; STYLE<hr>TASTING NOTES
						</div>';
			
		if($config[ConfigNames::ShowAbvCol]){ 
			$output .= '
						<div class="abv cell">
							CALORIES<hr>ALCOHOL
						</div>';
		}
		if($config[ConfigNames::ShowKegCol]){ 
			$output .= '
						<div class="keg cell">
							POURED<hr>REMAINING
						</div>';
		}
			
		return $output;
	}
	
	function getNumberOfColumns() {
		global $config;
		
		$count = 1;
		
		if($config[ConfigNames::ShowTapNumCol]){ 
			$count++;
		}
		if($config[ConfigNames::ShowSrmCol]){ 
			$count++;
		}
		if($config[ConfigNames::ShowIbuCol]){ 
			$count++;
		}
			
		if($config[ConfigNames::ShowAbvCol]){ 
			$count++;
		}
		if($config[ConfigNames::ShowKegCol]){ 
			$count++;
		}	
		
		return $count;
	}
	
	function outputTapListVertical() {
		global $beers, $numberOfTaps;
		
		$output = '';
		
		for($i = 1; $i <= $numberOfTaps; $i++) {
			$tapOutput = isset($beers[$i]) ? outputTap($i, $beers[$i]) : outputEmptyTap($i);
			
			$output .= '
					<div class="row">' . $tapOutput . '</div>';
		}
		
		return $output;
	}
	
	function outputTapListHorizontal() {
		global $beers, $numberOfTaps;
		
		$output = '';
		
		for($i = 1; $i <= 5; $i++) {
			$tapOutput = isset($beers[$i]) ? outputTap($i, $beers[$i]) : outputEmptyTap($i);
			$tapOutput .= isset($beers[$i+5]) ? outputTap($i+5, $beers[$i+5]) : outputEmptyTap($i+5);
			
			$output .= '
					<div class="row">' . $tapOutput . '</div>';
		}
		
		
		
		return $output;
	}
	
	function outputTap($num, $beer) {
		global $config;
		$output= '';
		
		$sideClass = $num <= 5 ? 'left' : 'right';
		
		if($config[ConfigNames::ShowTapNumCol]){
			$output .= '
						<div class="tap-num cell ' . $sideClass .'">
							<span class="tapcircle">' . $num . '</span>
						</div>';
		}
		
		if($config[ConfigNames::ShowSrmCol]){
			$output .= '
						<div class="srm cell ' . $sideClass .'">
							<h3>' . $beer['og'] . ' OG</h3>
							
							<div class="srm-container">
								<div class="srm-indicator" style="background-color: rgb(' . ($beer['srmRgb'] != "" ? $beer['srmRgb'] : "0,0,0") . ')"></div>
								<div class="srm-stroke"></div> 
							</div>
							
							<h2>' . $beer['srm'] . ' SRM</h2>
						</div>';
		}
		
		if($config[ConfigNames::ShowIbuCol]){
			$ibu = '0.00';
			
			if( $beer['og'] > 1 ){
				$ibu = number_format((($beer['ibu'])/(($beer['og']-1)*1000)), 2, '.', '');
			}
			
			$output .= '
						<div class="ibu cell ' . $sideClass .'">
							<h3>' . $ibu . ' BU:GU</h3>
							<div class="ibu-container">
								<div class="ibu-indicator"><div class="ibu-full" style="height:' . ($beer['ibu'] > 100 ? 100 : $beer['ibu']) . '%"></div></div>
							</div>
							<h2>' . $beer['ibu'] . ' IBU</h2>
						</div>';
		}
		
		$output .= '
						<div class="name cell ' . $sideClass .'">
							<h1>' . $beer['beername'] . '</h1>
							<h2 class="subhead">' . str_replace("_","",$beer['style']) . '</h2>
							<p>' . $beer['notes'] . '</p>
						</div>';
		
		if($config[ConfigNames::ShowAbvCol]){				
			$abv = calculateABV($beer);
			$abvOutput = '';
			
			if($config[ConfigNames::ShowAbvImg]) {
				$numCups = 0;
				$remaining = $abv * 20;
				do{
						if( $remaining < 100 ){
								$level = $remaining;
						}else{
								$level = 100;
						}
						$abvOutput .= '
								<div class="abv-indicator"><div class="abv-full" style="height:' . $level . '%"></div></div>';
						
						$remaining = $remaining - $level;
						$numCups++;
				}while($remaining > 0 && $numCups < 2);
				
				if( $remaining > 0 ){
					$abvOutput .= '
								<div class="abv-offthechart"></div>';
				}
			}
			
			$output .= '
						<div class="abv cell ' . $sideClass .'">
							<h3>' . number_format(calculateCalories($beer)) . ' kCal</h3>
							<div class="abv-container">' . $abvOutput . '				
							</div>
							<h2>' . number_format($abv, 1, '.', ',') . '% ABV</h2>
						</div>';
		}
			
		if($config[ConfigNames::ShowKegCol]){
			
			$percentRemaining = calcPercentRemaining($beer);
			$kegImgClass = getKegImgClass($beer, $percentRemaining);
			
			$output .= '
						<div class="keg cell ' . $sideClass .'">
							<h3>' . number_format((($beer['startAmount'] - $beer['remainAmount']) * 128)) . ' fl oz poured</h3>
							<div class="keg-container">
								<div class="keg-indicator">
									<div class="keg-full ' . $kegImgClass . '" style="height:' . $percentRemaining . '%"></div>
								</div>
							</div>
							<h2>' . number_format(($beer['remainAmount'] * 128)) . ' fl oz left</h2>
						</div>';
		}
	
		return $output;
	}
	
	function outputEmptyTap($i) {
		global $config;
		$output = '';
		
		if($config[ConfigNames::ShowTapNumCol]){
			$output .= '
						<div class="tap-num cell">
							<span class="tapcircle">' . $num . '</span>
						</div>';
		}
		
		if($config[ConfigNames::ShowSrmCol]){
			$output .= '
						<div class="srm cell">
							<h3></h3>
							<div class="srm-container">
								<div class="srm-indicator"></div>
								<div class="srm-stroke"></div>
							</div>
							<h2></h2>
						</div>';
		}
		
		if($config[ConfigNames::ShowIbuCol]){
			$output .= '
						<div class="ibu cell">
							<h3></h3>										
							<div class="ibu-container">
								<div class="ibu-indicator"><div class="ibu-full" style="height:0%"></div></div>
							</div>								
							<h2></h2>
						</div>';
		}
		
		$output .= '
						<div class="name cell">
							<h1>Nothing on tap</h1>
							<h2 class="subhead"></h2>
							<p></p>
						</div>';
		
		if(($config[ConfigNames::ShowAbvCol]) && ($config[ConfigNames::ShowAbvImg])){
			$output .= '
						<div class"abv cell">
							<h3></h3>
							<div class="abv-container">
								<div class="abv-indicator"><div class="abv-full" style="height:0%"></div></div>
							</div>
							<h2></h2>
						</div>';
		}
		
		if(($config[ConfigNames::ShowAbvCol]) && ! ($config[ConfigNames::ShowAbvImg])){
			$output .= '
						<div class"abv cell">
							<h3></h3>
							<h2></h2>
						</div>';
		}
		
		if($config[ConfigNames::ShowKegCol]){
			$output .= '
						<div class"keg cell">
							<h3></h3>
							<div class="keg-container">
								<div class="keg-indicator"><div class="keg-full keg-empty" style="height:0%"></div></div>
							</div>
							<h2>0 fl oz left</h2>
						</div>';
		}
		
		return $output;
	}

	function calculateCalories($beer) {
		$calfromalc = (1881.22 * ($beer['fg'] * ($beer['og'] - $beer['fg'])))/(1.775 - $beer['og']);
		$calfromcarbs = 3550.0 * $beer['fg'] * ((0.1808 * $beer['og']) + (0.8192 * $beer['fg']) - 1.0004);
		
		if ( ($beer['og'] == 1) && ($beer['fg'] == 1 ) ) {
			$calfromalc = 0;
			$calfromcarbs = 0;
		}
		
		return ($calfromalc + $calfromcarbs);	
	}
		
	function calculateABV($beer) {
		return ($beer['og'] - $beer['fg']) * 131;
	}
	function calcPercentRemaining($beer) {
		// Code for new kegs that are not full
		$tid = $beer['id'];
		$sql = "Select kegId from taps where id=".$tid." limit 1";
		$kegID = mysql_query($sql);
		$kegID = mysql_fetch_array($kegID);
		//echo $kegID[0];
		$sql = "SELECT `kegTypes`.`maxAmount` as kVolume FROM  `kegs`,`kegTypes` where  kegs.kegTypeId = kegTypes.id and kegs.id =".$kegID[0]."";
		$kvol = mysql_query($sql);
                $kvol = mysql_fetch_array($kvol);
		$kvol = $kvol[0];
		$kegImgClass = "";
		if ($beer['startAmount']>=$kvol) {
			$percentRemaining = $beer['remainAmount'] / $beer['startAmount'] * 100;
		} else {
			$percentRemaining =  $beer['remainAmount'] / $kvol * 100;
		}
		
		if($beer['remainAmount'] <= 0) {
			$percentRemaining = 100;
		}
		return $percentRemaining;
	}
	
	function getKegImgClass($beer, $percentRemaining) {
		if( $beer['remainAmount'] <= 0 ) {
			$kegImgClass = "keg-empty";
			$percentRemaining = 100; }
		else if( $percentRemaining < 15 )
			$kegImgClass = "keg-red";
		else if( $percentRemaining < 25 )
			$kegImgClass = "keg-orange";
		else if( $percentRemaining < 45 )
			$kegImgClass = "keg-yellow";
		else if ( $percentRemaining < 100 )
			$kegImgClass = "keg-green";
		else if( $percentRemaining >= 100 )
			$kegImgClass = "keg-full";
		
		return $kegImgClass;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<title>RaspberryPints</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<!-- Set location of Cascading Style Sheet -->
		<link rel="stylesheet" type="text/css" href="style.css">
		
		<?php if($config[ConfigNames::UseHighResolution]) { ?>
			<link rel="stylesheet" type="text/css" href="high-res.css">
		<?php } ?>
		
		<link rel="shortcut icon" href="img/pint.ico">
		<script src="jquery-3.2.1.slim.min.js" type="text/javascript"></script>
		<script>
		function setNameColumnWidths() {
			var rowWidth = $('.row:nth(1)').width();
			var nameWidth = 0;
			$('.row:nth(1) .name.cell').each(function sumWidths(index){
				nameWidth += $(this).width();
			});
			
			$('.row:nth(1) .name.cell').each(function setWidth(index) {
				$(this).width(nameWidth / 2);
			});
			
		}
		
		$(document).ready(function docReady() {
			setNameColumnWidths();
			
			$(window).resize(setNameColumnWidths);
		});
		</script>
	</head> 

	<body>
		<div class="bodywrapper">
			<!-- Header with Brewery Logo and Project Name -->
			<div class="pageHeader clearfix">
				<div class="HeaderLeft">
					<?php if($config[ConfigNames::UseHighResolution]) { ?>			
						<a href="admin/admin.php"><img src="<?php echo $config[ConfigNames::LogoUrl] . "?" . time(); ?>" height="200" alt=""></a>
					<?php } else { ?>
						<a href="admin/admin.php"><img src="<?php echo $config[ConfigNames::LogoUrl] . "?" . time(); ?>" height="100" alt=""></a>
					<?php } ?>
				</div>
				<div class="HeaderCenter">
					<h1 id="HeaderTitle">
						<?php
							if (mb_strlen($config[ConfigNames::HeaderText], 'UTF-8') > ($config[ConfigNames::HeaderTextTruncLen])) {
								$headerTextTrunced = substr($config[ConfigNames::HeaderText],0,$config[ConfigNames::HeaderTextTruncLen]) . "...";
								echo $headerTextTrunced ; }
							else
								echo $config[ConfigNames::HeaderText];
						?>
					</h1>
				</div>
				<div class="HeaderRight">
					<?php if($config[ConfigNames::UseHighResolution]) { ?>			
						<a href="http://www.raspberrypints.com"><img src="img/RaspberryPints-4k.png" height="200" alt=""></a>
					<?php } else { ?>
						<a href="http://www.raspberrypints.com"><img src="img/RaspberryPints.png" height="100" alt=""></a>
					<?php } ?>
				</div>
			</div>
			<!-- End Header Bar -->
			<!-- Start Tap Table -->
			<div class="table <?php echo ($config[ConfigNames::DisplayHorizontal] ? 'horizontal' : 'vertical');?>">
				<div class="header">
					<div class="row"><?php 
						echo outputHeaders(); 
						if($config[ConfigNames::DisplayHorizontal] && $numberOfTaps > 5) {
							echo  outputHeaders();
						}								
					?>
					
					</div>
				</div>
				<div class="body"><?php
				
						if($config[ConfigNames::DisplayHorizontal] && $numberOfTaps > 5) {
							echo outputTapListHorizontal();
						}
						else {
							echo outputTapListVertical();
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>