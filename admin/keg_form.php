<?php
require_once __DIR__.'/header.php';

$htmlHelper = new HtmlHelper();
$kegManager = new KegManager();
$kegStatusManager = new KegStatusManager();
$kegTypeManager = new KegTypeManager();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$keg = new Keg();
	$keg->setFromArray($_POST);
	$kegManager->Save($keg);
	redirect('keg_list.php');
}

if( isset($_GET['id'])){
	$keg = $kegManager->GetById($_GET['id']);
}else{
	$keg = new Keg();
}

$kegStatusList = $kegStatusManager->GetAll();
$kegTypeList = $kegTypeManager->GetAll();
?>
<!-- Start Header -->
<?php
include 'top_menu.php';
?>
<!-- End Header -->
		
	<!-- Top Breadcrumb Start -->
	<div id="breadcrumb">
		<ul>	
			<li><img src="img/icons/icon_breadcrumb.png" alt="Location" /></li>
			<li><strong>Location:</strong></li>
			<li><a href="keg_list.php">Keg List</a></li>
			<li>/</li>
			<li class="current">Keg Form</li>
		</ul>
	</div>
	<!-- Top Breadcrumb End --> 
	
	<!-- Right Side/Main Content Start -->
	<div id="rightside">
		<div class="contentcontainer med left">
	<p>
		fields marked with an * are required

	<form id="keg-form" method="POST">
		<input type="hidden" name="id" value="<?php echo $keg->get_id() ?>" />

		<table width="950" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					Label: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="label" class="mediumbox" name="label" value="<?php echo $keg->get_label() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Type: <b><font color="red">*</color></b>
				</td>
				<td>
					<?php echo $htmlHelper->ToSelectList("kegTypeId", $kegTypeList, "name", "id", $keg->get_kegTypeId(), "Select One"); ?>
				</td>
			</tr>	
			<tr>
				<td>
					Make: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="make" class="mediumbox" name="make" value="<?php echo $keg->get_make() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Model: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="model" class="mediumbox" name="model" value="<?php echo $keg->get_model() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Serial: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="serial" class="mediumbox" name="serial" value="<?php echo $keg->get_serial() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Stamped Owner: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="stampedOwner" class="mediumbox" name="stampedOwner" value="<?php echo $keg->get_stampedOwner() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Stamped Location: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="stampedLoc" class="mediumbox" name="stampedLoc" value="<?php echo $keg->get_stampedLoc() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Empty Weight: <b><font color="red">*</color></b>
				</td>
				<td>
					<input type="text" id="weight" class="mediumbox" name="weight" value="<?php echo $keg->get_weight() ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Notes: <b><font color="red">*</color></b>
				</td>
				<td>
					<textarea id="notes" class="text-input textarea" name="notes" style="width:500px;height:100px"><?php echo $keg->get_stampedOwner() ?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Status: <b><font color="red">*</color></b>
				</td>
				<td>
					<?php echo $htmlHelper->ToSelectList("kegStatusCode", $kegStatusList, "name", "code", $keg->get_kegStatusCode(), "Select One"); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input name="save" type="submit" class="btn" value="Save" />
					<input type="button" class="btn" value="Cancel" onclick="window.location='keg_list.php'" />
				</td>
			</tr>								
		</table>
		<br />
		<div align="right">			
			&nbsp &nbsp 
		</div>

	</form>
	</div>
	<!-- End On Tap Section -->

	<!-- Start Footer -->   
<?php 
include 'footer.php';
?>

	<!-- End Footer -->
		
	</div>
	<!-- Right Side/Main Content End -->
	<!-- Start Left Bar Menu -->   
<?php 
include 'left_bar.php';
?>
	<!-- End Left Bar Menu -->  
	<!-- Start Js  -->
<?php
include 'scripts.php';
?>

<script>
	$(function() {		
		
		$('#keg-form').validate({
			rules: {
				label: { required: true, number: true },
				kegTypeId: { required: true },
				kegStatusCode: { required: true },
				make: { required: true },
				model: { required: true },
				serial: { required: true },
				stampedOwner: { required: true },
				stampedLoc: { required: true },
				weight: { required: true },
				notes: { required: true }
			}
		});
		
	});
</script>

	<!-- End Js -->
	<!--[if IE 6]>
	<script type='text/javascript' src='scripts/png_fix.js'></script>
	<script type='text/javascript'>
	DD_belatedPNG.fix('img, .notifycount, .selected');
	</script>
	<![endif]--> 
	
</body>
</html>
