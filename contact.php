<?php require_once('includes/config.php');
include('includes/sc-includes.php');
$pagetitle = 'Contact';

$update = 0;
if (isset($_GET['id'])) {
$update = 1;
}

//
record_set('contact',"SELECT * FROM contacts WHERE contact_id = -1");
if ($update==1) {
record_set('contact',"SELECT * FROM contacts LEFT JOIN fields_assoc ON cfield_contact = contact_id WHERE contact_id = ".$_GET['id']."");
}
//

//if not admin and trying to edit a contact that they didn't add
if ($userid != $row_contact['contact_user'] && !$user_admin && $update) {
	redirect('You cannot edit this contact.',$_SERVER['HTTP_REFERER']);
}
//

//contact state
//if US
$state = $_POST['contact_state'];

//if Canada
if ($_POST['contact_state_ca'] && $_POST['contact_country'] == 'CA') {
	$state = $_POST['contact_state_ca'];
}

//if not US and not CA
if ($_POST['contact_country'] != 'US' && $_POST['contact_country'] != 'CA' && $_POST['contact_state_b']) {
	$state = $_POST['contact_state_b'];
}
//

//UPLOAD PICTURE
	$picture = "";
if (!empty($_POST['image_location'])) {
	$picture = $_POST['image_location'];
}
	$time = substr(time(),0,5);	
if ($_FILES) {
   if($_FILES['image'] && $_FILES['image']['size'] > 0){
	$ori_name = $_FILES['image']['name'];
	$ori_name = $time.$ori_name;
	$tmp_name = $_FILES['image']['tmp_name'];
	$src = imagecreatefromjpeg($tmp_name);
	list($width,$height)=getimagesize($tmp_name);
	$newwidth=95;
	$newheight=($height/$width)*95;
	$tmp=imagecreatetruecolor($newwidth,$newheight);
	imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
	$filename = "images/". $ori_name;
	imagejpeg($tmp,$filename,100);
	$picture = $ori_name;
	imagedestroy($src);
	imagedestroy($tmp);	
}
}
//END UPLOAD PICTURE

//add contact

if (!$update && $_POST['contact_first']) {

  mysql_query("INSERT INTO contacts (contact_tags, contact_first, contact_last, contact_title, contact_image, contact_profile, contact_company, contact_street, contact_city, contact_state, contact_country, contact_zip, contact_phone, contact_cell, contact_email, contact_web, contact_updated, contact_user) VALUES 

	(
		'".insert('contact_tags')."',
		'".insert('contact_first')."',
		'".insert('contact_last')."',
		'".insert('contact_title')."',
		'".$picture."',
		'".insert('contact_profile')."',
		'".insert('contact_company')."',
		'".insert('contact_street')."',
		'".insert('contact_city')."',
		'".$state."',
		'".insert('contact_country')."',
		'".insert('contact_zip')."',
		'".insert('contact_phone')."',
		'".insert('contact_cell')."',
		'".insert('contact_email')."',
		'".insert('contact_web')."',
		'".time()."',
		'".$userid."'
	)

	");

$cid = mysql_insert_id();

//add extra fields
record_set('fields',"SELECT * FROM fields ORDER BY field_title ASC");
do {

	$fv = "";
	
	if ($_POST['contact_f_'].$row_fields['field_id']) {
		
		$fv = $_POST['contact_f_'.$row_fields['field_id']];
		
		if (!empty($fv)) {
		mysql_query("INSERT INTO fields_assoc (cfield_field, cfield_contact, cfield_value) VALUES
			
			(
				'".$row_fields['field_id']."',
				'".$cid."',
				'".$fv."'
			)
		
		");
		}
	}

} while ($row_fields = mysql_fetch_assoc($fields));
//end add extra fields

//insert tags
$tags = str_replace("","",addslashes($_POST['contact_tags']));
$tags = explode(",",$tags);

foreach ($tags as $key => $value) {

$value = trim($value);

	if ($value) {
		mysql_query("DELETE FROM tags WHERE tag_description = '".addslashes($value)."'");
		mysql_query("INSERT INTO tags (tag_description) VALUES
		
		(
			'".addslashes($value)."'
		)
		
		");
	$tid = mysql_insert_id();

	//associate tag with contact
	mysql_query("INSERT INTO tags_assoc (itag_contact, itag_tag) VALUES
	(
		'".$cid."',
		'".$tid."'
	)
	");
	//
}

}

	$redirect = "contact-details.php?id=$cid";
	redirect('Contact Added',$redirect);
}
//end add contact

//update contact


if ($update && $_POST &&  $_POST['contact_first']) {

mysql_query("UPDATE contacts SET

	contact_tags = '".insert('contact_tags')."',
	contact_first = '".insert('contact_first')."',
	contact_last = '".insert('contact_last')."',
	contact_title = '".insert('contact_title')."',
	contact_image = '".insert('contact_image')."',
	contact_profile = '".insert('contact_profile')."',
	contact_company = '".insert('contact_company')."',
	contact_street = '".insert('contact_street')."',
	contact_city = '".insert('contact_city')."',
	contact_state = '".$state."',
	contact_country = '".insert('contact_country')."',
	contact_zip = '".insert('contact_zip')."',
	contact_phone = '".insert('contact_phone')."',
	contact_cell = '".insert('contact_cell')."',
	contact_email = '".insert('contact_email')."',
	contact_web = '".insert('contact_web')."',
	contact_updated = '".time()."'

WHERE contact_id = ".$_GET['id']."
");

//add extra fields
mysql_query("DELETE FROM fields_assoc WHERE cfield_contact = ".$_GET['id']."");
record_set('fields',"SELECT * FROM fields ORDER BY field_title ASC");
do {

	$fv = "";
	
	if ($_POST['contact_f_'].$row_fields['field_id']) {
		
		$fv = $_POST['contact_f_'.$row_fields['field_id']];
		
		if (!empty($fv)) {
		mysql_query("INSERT INTO fields_assoc (cfield_field, cfield_contact, cfield_value) VALUES
			
			(
				'".$row_fields['field_id']."',
				'".$_GET['id']."',
				'".$fv."'
			)
		
		");
		}
	}

} while ($row_fields = mysql_fetch_assoc($fields));
//end add extra fields

	$pid = $_GET['id'];

//insert tags
mysql_query("DELETE FROM tags_assoc WHERE itag_contact = ".$_GET['id']."");
$tags = str_replace("","",addslashes($_POST['contact_tags']));
$tags = explode(",",$tags);

foreach ($tags as $key => $value) {

$value = trim($value);

	if ($value) {
		mysql_query("DELETE FROM tags WHERE tag_description = '".addslashes($value)."'");
		mysql_query("INSERT INTO tags (tag_description) VALUES
		
		(
			'".addslashes($value)."'
		)
		
		");
	$tid = mysql_insert_id();

	//associate tag with contact
	mysql_query("INSERT INTO tags_assoc (itag_contact, itag_tag) VALUES
	(
		'".$pid."',
		'".$tid."'
	)
	");
	//

	}

}

//

	$cid = $_GET['id'];
	$redirect = "contact-details.php?id=$cid";

	redirect('Contact Updated',$redirect);
}

//custom fields
record_set('fields',"SELECT * FROM fields ORDER BY field_title ASC");
//

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php if ($update==0) { echo "Add Contact"; } ?><?php echo $row_contact['contact_first']; ?> <?php echo $row_contact['contact_last']; ?></title>
<script src="includes/lib/prototype.js" type="text/javascript"></script>
<script src="includes/src/effects.js" type="text/javascript"></script>
<script src="includes/validation.js" type="text/javascript"></script>
<script src="includes/src/scriptaculous.js" type="text/javascript"></script>
<script language="javascript">
function toggleLayer(whichLayer)
{
if (document.getElementById)
{
// this is the way the standards work
var style2 = document.getElementById(whichLayer).style;
style2.display = style2.display? "":"block";
}
else if (document.all)
{
// this is the way old msie versions work
var style2 = document.all[whichLayer].style;
style2.display = style2.display? "":"block";
}
else if (document.layers)
{
// this is the way nn4 works
var style2 = document.layers[whichLayer].style;
style2.display = style2.display? "":"block";
}
}
</script>


<script type="text/javascript">

<!--COUNTRY/STATE
function showState(d) {
	if(d=="US") {
	document.getElementById("state").style.display="block";
	document.getElementById("state_b").style.display="none";
	document.getElementById("state_canada").style.display="none";
	}
	
	if (d=="CA") {
	document.getElementById("state_canada").style.display="block";
	document.getElementById("state_b").style.display="none";
	document.getElementById("state").style.display="none";
	}
	
	if (d!="CA" && d!="US") {
	document.getElementById("state_canada").style.display="none";
	document.getElementById("state_b").style.display="block";
	document.getElementById("state").style.display="none";
	}

}

//-->

</script>

<link href="includes/style.css" rel="stylesheet" type="text/css" />
<link href="includes/layout.css" rel="stylesheet" type="text/css" />
</head>

<body <?php if ($row_contact['contact_state']) { ?>onload="showState('<?php echo $row_contact['contact_state']; ?>')"<?php } ?>>
<?php include('includes/header.php'); ?>
<div class="container">
  <div class="leftcolumn">
    <h2><?php if ($update==1) { echo 'Update'; } else { echo 'Add'; } ?> Contact </h2>
    <p>&nbsp;</p>
    <form action="" method="POST" enctype="multipart/form-data" name="form1" id="form1">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="28%">First Name<br />
            <input name="contact_first" type="text" class="required" id="contact_first" value="<?php echo $row_contact['contact_first']; ?>" size="25" /></td>
          <td width="72%">Last Name<br />
                <input name="contact_last" type="text" class="required" id="contact_last" value="<?php echo $row_contact['contact_last']; ?>" size="25" />
            </p></td>
        </tr>
        <tr>
          <td>Title<br />            <input name="contact_title" type="text" id="contact_title" value="<?php echo $row_contact['contact_title']; ?>" size="25" />          </td>
          <td>Company<br />
            <input name="contact_company" type="text" id="contact_company" value="<?php echo $row_contact['contact_company']; ?>" size="35" /></td>
        </tr>
        <tr>
          <td colspan="2">Email <br />
            <input name="contact_email" type="text" class="required validate-email" id="contact_email" value="<?php echo $row_contact['contact_email']; ?>" size="35" /></td>
        </tr>
        <tr>
          <td colspan="2"><hr />
         <?php if ($update!=1) { ?>   <p><a href="#" onclick="new Effect.toggle('morecontact', 'slide'); return false;">+Add more contact information </a></p>
         <br />
         <?php } ?>

<div <?php if ($update!=1) { ?>id="morecontact" style="display:none"<?php } ?>>
            <table  width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>Street<br />
                    <input name="contact_street" type="text" id="contact_street" value="<?php echo $row_contact['contact_street']; ?>" size="35" /></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td colspan="3">Country<br />
                        <select id="contact_country" name="contact_country" onchange="showState(this.value)">

<?php foreach ($country_list as $key => $value) { ?>
                          <option value="<?php echo $key; ?>" <?php selected($key,$row_contact['contact_country']); ?>><?php echo $value; ?></option>
<?php } ?>

       
                        </select></td>
                      </tr>
                    <tr>
                      <td width="39%">City<br />
                          <input name="contact_city" type="text" id="contact_city" value="<?php echo $row_contact['contact_city']; ?>" size="35" /></td>
                      <td width="27%" valign="top"></td>
                      <td width="34%">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td>State<br />

                  <div id="state" style="display:block; margin:0px; padding:0px">
                    <select name="contact_state" id="contact_state">
                      <option value="">Select a state...</option>
                      <?php foreach ($state_list as $key => $value) { ?>
                      <option value="<?php echo $key; ?>" <?php selected($key,$row_contact['contact_state']); ?>><?php echo $value; ?></option>
                      <?php } ?>
                    </select>
                  </div>

<div id="state_canada" style="display:none">
<select id="contact_state_ca" name="contact_state_ca">

<?php foreach ($state_list_ca as $key => $value) { ?>
        <option value="<?php echo $key; ?>" <?php selected($key,$row_contact['contact_state']); ?>><?php echo $value; ?> (<?php echo $key; ?>)</option>
<?php } ?>

</select>
</div>


<!--if not US or Canada-->
       <div id="state_b" style="display:none; margin:0px; padding:0px">
            <input id="contact_state_b" name="contact_state_b" type="text" value="<?php echo $row_contact['contact_state']; ?>" size="14" />
        </div>
<!--end alternate state--></td>
              </tr>
              <tr>
                <td>Postal Code <br />
                    <input name="contact_zip" type="text" id="contact_zip" value="<?php echo $row_contact['contact_zip']; ?>" size="10" /></td>
              </tr>
              
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="39%">Phone<br />
                          <input name="contact_phone" type="text" id="contact_phone" value="<?php echo $row_contact['contact_phone']; ?>" size="35" /></td>
                      <td width="61%">Cell<br />
                          <input name="contact_cell" type="text" id="contact_cell" value="<?php echo $row_contact['contact_cell']; ?>" size="35" /></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td>Image<br />
                    <input name="image" type="file" id="image" /><?php if ($row_contact['contact_image']) { ?>
                <br />
                <img src="images/<?php echo $row_contact['contact_image']; ?>" width="95" />
<?php } ?></td>
              </tr>
              <tr>
                <td>Website<br />
                    <input name="contact_web" type="text" id="contact_web" value="<?php echo $row_contact['contact_web']; ?>" size="45" /></td>
              </tr>
              <tr>
                <td>Background/Profile<br />
                    <textarea name="contact_profile" cols="60" rows="3" id="contact_profile"><?php echo $row_contact['contact_profile']; ?></textarea>
<br />


<!--custom fields-->
<?php if ($totalRows_fields) do { 

if ($update) {
record_set('fieldv',"SELECT * FROM fields_assoc WHERE cfield_field = ".$row_fields['field_id']." AND cfield_contact = ".$_GET['id']."");
}

$cvalue = $row_fieldv['cfield_value'];

?>
<br />
<?php echo $row_fields['field_title']; ?><br />

<?php if (!$row_fields['field_content']) { ?>
<input name="contact_f_<?php echo $row_fields['field_id']; ?>" type="text" id="contact_f_<?php echo $row_fields['field_id']; ?>" value="<?php echo $cvalue; ?>" size="45" /><br />
<?php } ?>

<?php if ($row_fields['field_content']) { ?>
<select name="contact_f_<?php echo $row_fields['field_id']; ?>" id="contact_f_<?php echo $row_fields['field_id']; ?>">

<option value="">Select from below...</option>

<?php 
$content_sep = explode(",",$row_fields['field_content']);
foreach ($content_sep as $key => $value) { ?>

<option value="<?php echo $value; ?>" <?php selected($cvalue,$value); ?>><?php echo $value; ?></option>

<?php } ?>
</select>
<br />

<?php } ?>

<?php } while ($row_fields = mysql_fetch_assoc($fields)); ?>
<!--end custom fields-->

</td>
              </tr>
            </table>  
</div>          
          <p>&nbsp;</p></td>
        </tr>



        <tr>
          <td colspan="2">Tags<br />
          <input name="contact_tags" type="text" id="contact_tags" value="<?php echo $row_contact['contact_tags']; ?>" size="45" /></td>
        </tr>
        <tr>
          <td colspan="2"><p>
            <input type="submit" name="Submit2" value="<?php echo $update==1 ? 'Update' : 'Add'; ?> contact" />
          </p></td>
        </tr>
      </table>
    </form>

<script type="text/javascript">
	var valid2 = new Validation('form1', {useTitles:true});
</script>

  </div>
  <?php include('includes/right-column.php'); ?>

  <br clear="all" />
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
