<?php require_once('includes/config.php'); 
include('includes/sc-includes.php');
$pagetitle = 'Contact';

//restrict if not admin
if (!$user_admin) {
header('Location: contacts.php'); die;
}
//

//custom fields
record_set('fields',"SELECT * FROM fields ORDER BY field_title ASC");
//

if ($_POST) {

foreach ($_POST['field'] as $key => $value) {
if ($value) {
$value = addslashes($value);
mysql_query("UPDATE fields SET 
	field_title = '".$value."',
	field_content = '".addslashes($_POST['field_content_update'][$key])."'
WHERE field_id = ".$key."");
}
}

//add new field
if ($_POST['field_add']) {
mysql_query("INSERT INTO fields (field_title, field_content) VALUES 
	(
		'".insert('field_add')."',
		'".insert('field_content')."'
	)
");
}

redirect('Your changes have been saved.','fields.php');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Custom Fields</title>
<script src="includes/lib/prototype.js" type="text/javascript"></script>
<script src="includes/src/effects.js" type="text/javascript"></script>
<script src="includes/validation.js" type="text/javascript"></script>
<link href="includes/style.css" rel="stylesheet" type="text/css" />
<link href="includes/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include('includes/header.php'); ?>
<div class="container">
  <div class="leftcolumn">
    <h2>Custom Fields </h2>
    <?php display_msg(); ?>    


    <form id="form1" name="form1" method="post" action="">

    <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <th colspan="3" scope="row"><br />
          Field Name <br />
          <input name="field_add" id="field_add" size="35" />
          <br />
          <br />
          Optional drop down menu options<em> (separate by comma) </em><br />
          <input name="field_content" id="field_content" size="45" />
          <br />
          <br />
          <input name="Submit22" type="submit" id="Submit22" value="Add" />
          <br />
          <br /></th>
        </tr>
      <tr>
        <th bgcolor="#EBEBEB" scope="row">Current Fields </th>
        <td bgcolor="#EBEBEB">&nbsp;</td>
        <td bgcolor="#EBEBEB">&nbsp;</td>
      </tr>
      <tr>
        <th width="39%" bgcolor="#F4F4F4" scope="row">Field Name </th>
        <td width="53%" bgcolor="#F4F4F4"><strong>Optional Dropdown Menu Options </strong></td>
        <td width="8%" bgcolor="#F4F4F4">&nbsp;</td>
      </tr>


<?php if ($totalRows_fields) do { ?>
      <tr>
        <th bgcolor="#F4F4F4" scope="row"><input name="field[<?php echo $row_fields['field_id']; ?>]" id="field[<?php echo $row_fields['field_id']; ?>]" value="<?php echo $row_fields['field_title']; ?>" size="35" /></th>
        <td nowrap="nowrap" bgcolor="#F4F4F4"><input name="field_content_update[<?php echo $row_fields['field_id']; ?>]" id="field_content_update[<?php echo $row_fields['field_id']; ?>]" value="<?php echo $row_fields['field_content']; ?>" size="35" /></td>
        <td nowrap="nowrap" bgcolor="#F4F4F4"><a href="delete.php?field=<?php echo $row_fields['field_id']; ?>" onclick="javascript:return confirm('Are you sure?')">Delete</a> </td>
      </tr>
<?php } while ($row_fields = mysql_fetch_assoc($fields)); ?>
    </table>

      <p>&nbsp;</p>
      <p>
<?php if ($totalRows_fields) { ?>
        <input name="Submit2" type="submit" id="Submit2" value="Update" /> 
<?php } ?>


      </p>
    </form>
    
    <p>&nbsp;</p>
  </div>
  <?php include('includes/right-column.php'); ?>
  <br clear="all" />
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
