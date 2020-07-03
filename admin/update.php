<?php
session_start();
if(!isset( $_SESSION['myusername'] )){
  header("location:index.php");
}
use RaspberryPints\DB;
$DB = DB::getInstance();

// get value of id that sent from address bar
$beerid = $_GET['beerid'];

// Retrieve data from database
$sql = "SELECT * FROM taps WHERE beerid = ?";
$result = $DB->get($sql, [
  ['type' => DB::BIND_TYPE_INT, 'value' => $beerid]
]);
if(count($result) == 0) {
  throw new Exception("Beer not found for beer id \"$beerid\".");
}
?>

<head>
<title>Beer List</title>
<link href="styles.css" rel="stylesheet" type="text/css" />
</head>
<table align="center">
<tr>
<form name="form1" method="post" action="push_update.php">
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="0">
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td align="center"><strong>Update Your Beers Information</strong> </td>
</tr>
<tr>
<td align="center">&nbsp;</td></tr>
<tr><td align="center"><strong>Tap Number</strong></td></tr>
<tr><td align="center"><input class="smallbox" name="tapnumber" type="text" id="tapnumber" value="<? echo $result[0]['tapnumber']; ?>"></td></tr>
<tr><td align="center"><strong>Name</strong></td></tr>
<tr><td align="center"><input class="mediumbox" name="name" type="text" id="name" value="<? echo $result[0]['name']; ?>"></td></tr>
<tr><td align="center"><strong>Style</strong></td></tr>
<tr><td align="center"><input class="mediumbox" name="style" type="text" id="style" value="<? echo $result[0]['style']; ?>"></td></tr>
<tr><td align="center"><strong>Notes</strong></td></tr>
<tr><td align="center"><textarea class="inputbox" name="notes" rows="5" cols="50"><? echo $result[0]['notes']; ?></textarea></td></tr>
<tr><td align="center"><strong>OG</strong></td> </tr>
<tr><td align="center"><input class="smallbox" name="og" type="text" id="og" value="<? echo $result[0]['og']; ?>"</td></tr>
<tr><td align="center"><strong>FG</strong></td></tr>
<tr><td align="center"><input class="smallbox" name="fg" type="text" id="fg" value="<? echo $result[0]['fg']; ?>"</td></tr>
<tr><td align="center"><strong>SRM</strong></td></tr>
<tr><td align="center"><input class="smallbox" name="srm" type="text" id="srm" value="<? echo $result[0]['srm']; ?>"></td></tr>
<tr><td align="center"><strong>IBU's</strong></td></tr>
<tr><td align="center"><input class="smallbox" name="ibu" type="text" id="ibu" value="<? echo $result[0]['ibu']; ?>"></td></tr>
<tr><td align="center"><strong>Active (1-Yes 2-No)</strong></td></tr>
<tr><td align="center"><input class="smallbox" name="active" type="text" id="active" value="<? echo $result[0]['active']; ?>" size="3"></td></tr>
<tr><td>
<input name="beerid" type="hidden" id="beerid" value="<? echo $result[0]['beerid']; ?>">
</td></tr>
<tr>
<td align="center">
<input type="submit" class="btn" name="Submit" value="Submit"> &nbsp <input type="button" class="btn"  onclick="window.history.back()" value="Go Back">
</td>
<td>&nbsp;</td>
</tr>
</table>
</td>
</form>
</tr>
</table>
