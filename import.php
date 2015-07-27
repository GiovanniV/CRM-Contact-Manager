<?php require_once('includes/config.php'); 
ob_start();

include('includes/sc-includes.php');
$pagetitle = 'Contact';

if (!empty($_GET['csv']) && $_GET['csv'] == 'import') { 

$row = 1;
$handle = fopen ($_FILES['csv']['tmp_name'],"r");

	$cf = array();

while ($data = fgetcsv($handle, 1000, ",")) {

//custom field array
if ($row == 1) {
	foreach ($data as $key => $value) {
		if ($key > 14) {
			$cf[$key] = $value; 
		}
	}
}
//

//end add extra fields

$checkc = mysql_num_rows(mysql_query("SELECT * FROM contacts WHERE contact_id = ".$data[0].""));

	if ($checkc > 0) {

mysql_query("UPDATE contacts SET

	contact_first = '".addslashes($data[1])."',
	contact_last = '".addslashes($data[2])."',
	contact_title = '".addslashes($data[3])."',
	contact_company = '".addslashes($data[4])."',
	contact_street = '".addslashes($data[5])."',
	contact_city = '".addslashes($data[6])."',
	contact_state = '".addslashes($data[7])."',
	contact_zip = '".addslashes($data[8])."',
	contact_country = '".addslashes($data[9])."',
	contact_email = '".addslashes($data[10])."',
	contact_phone = '".addslashes($data[11])."',
	contact_cell = '".addslashes($data[12])."',
	contact_web = '".addslashes($data[13])."',
	contact_profile = '".addslashes($data[14])."'

WHERE contact_id = ".$data['0']."
");

}
//

else { 


if ($row > 1) {

//INSERT NEW RECORDS
mysql_query("INSERT INTO contacts (contact_first, contact_last, contact_title, contact_company, contact_street, contact_city, contact_state, contact_zip, contact_country, contact_email, contact_phone, contact_fax, contact_web, contact_profile) VALUES

(
	   '".addslashes($data[1])."',
	   '".addslashes($data[2])."',
	   '".addslashes($data[3])."',
	   '".addslashes($data[4])."',
	   '".addslashes($data[5])."',
	   '".addslashes($data[6])."',
	   '".addslashes($data[7])."',
	   '".addslashes($data[8])."',
	   '".addslashes($data[9])."',
	   '".addslashes($data[10])."',
	   '".addslashes($data[11])."',
	   '".addslashes($data[12])."',
	   '".addslashes($data[13])."',
	   '".addslashes($data[14])."'
)

");

	$cid = mysql_insert_id();

//add extra fields
foreach ($cf as $key => $value) {

record_set('fields',"SELECT * FROM fields WHERE field_title = '".addslashes($value)."'");

	if ($totalRows_fields) {
		mysql_query("INSERT INTO fields_assoc (cfield_field, cfield_contact, cfield_value) VALUES
			
			(
				'".$row_fields['field_id']."',
				'".$cid."',
				'".addslashes($data[$key])."'
			)
		
		");
	}

}
//end add extra fields

mysql_query("INSERT INTO history (history_contact, history_date, history_status) VALUES
(
	".$cid.",
	".time().",
	1
)
");

//
}
$row++;
}

}


header('Location: contacts.php');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $pagetitle; ?>s</title>
<script src="includes/lib/prototype.js" type="text/javascript"></script>
<script src="includes/src/effects.js" type="text/javascript"></script>
<script src="includes/validation.js" type="text/javascript"></script>
<script src="includes/src/scriptaculous.js" type="text/javascript"></script>

<link href="includes/style.css" rel="stylesheet" type="text/css" />
<link href="includes/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include('includes/header.php'); ?>
  
  <div class="container">
  <div class="leftcolumn">
    <h2> Import Contacts </h2>
    <table width="540" border="0" cellpadding="0" cellspacing="0">
      
      <tr>
        <td colspan="2">Click on &quot;Export Contacts&quot; below to see how to set up your CSV file for importing.</td>
      </tr>
      <tr>
        <td colspan="2"><form name="form1" id="form1" enctype="multipart/form-data" method="post" action="?csv=import">
            <input name="csv" type="file" id="csv" size="40" />
            <br />
            <input name="submit" type="submit" value="Import File" />
            <a href="csv.php"></a> 
        </form></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><a href="csv.php"><strong>+ Export Contacts</strong></a></td>
      </tr>
    </table>    
    <p>&nbsp; </p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
  <?php include('includes/right-column.php'); ?>
  <br clear="all" />
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
