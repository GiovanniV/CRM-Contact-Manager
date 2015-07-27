<?php require_once('includes/config.php'); ?>
<?php
include('includes/sc-includes.php');

$pagetitle = 'ContactDetails';

$update = 0;
if (isset($_GET['note'])) {
$update = 1;
}

//contact
record_set('contact',"SELECT * FROM contacts WHERE contact_id = ".$_GET['id']."");

//notes
record_set('notes',"SELECT * FROM notes WHERE note_contact = ".$_GET['id']." ORDER BY note_date DESC");

record_set('note',"SELECT * FROM notes WHERE note_id = -1");
if ($update==1) {
record_set('note',"SELECT * FROM notes WHERE note_id = ".$_GET['note']."");
}

//INSERT NOTE FOR CONTACT
if ($update==0 && !empty($_POST['note_text'])) {
	mysql_query("INSERT INTO notes (note_contact, note_text, note_date, note_status) VALUES 
		(
		".$row_contact['contact_id'].",
		'".addslashes($_POST['note_text'])."',
		".time().",
		1
		)
	");
	
	$goto = "contact-details.php?id=$_GET[id]";
	redirect('Note Added',$goto);
}
//

//UPDATE NOTE
if ($update==1 && !empty($_POST['note_text'])) {
	mysql_query("UPDATE notes SET 
		note_text = '".addslashes($_POST['note_text'])."' 
	WHERE note_id = ".$_GET['note']."");

	$goto = "contact-details.php?id=$_GET[id]";
	redirect('Note Updated',$goto);
}
//


//UPDATE HISTORY
record_set('checkhistory',"SELECT history_contact FROM history WHERE history_contact = ".$_GET['id']."");

if ($totalRows_checkhistory > 0) { 
mysql_query("UPDATE history SET history_status = 2 WHERE history_contact = ".$_GET['id']."");
}

mysql_query("INSERT INTO history (history_contact, history_date, history_status) VALUES
(
	".$row_contact['contact_id'].",
	".time().",
	1
)
");

//

//can this user edit this contact?
$can_edit = 0;
if ($user_admin || $userid == $row_contact['contact_id']) {
$can_edit = 1;
}
//

//automatically add custom field data to contacts contact_custom field
record_set('cfields',"SELECT * FROM fields_assoc WHERE cfield_contact = ".$_GET['id']."");
do {
	$data .= $row_cfields['cfield_value'].", ";
	mysql_query("UPDATE contacts SET contact_custom = '".$data."' WHERE contact_id = ".$_GET['id']."");
} while ($row_cfields = mysql_fetch_assoc($cfields));
//

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $row_contact['contact_first']; ?> <?php echo $row_contact['contact_last']; ?></title>

<script src="includes/src/unittest.js" type="text/javascript"></script>
<link href="includes/style.css" rel="stylesheet" type="text/css" />
<link href="includes/layout.css" rel="stylesheet" type="text/css" />
</head>

<body <?php if ($row_notes['note_date'] > time()-1) { ?>onload="new Effect.Highlight('newnote'); return false;"<?php } ?>>
<?php include('includes/header.php'); ?>
<div class="container">
  <div class="leftcolumn">

    <?php display_msg(); ?>

<div style="display:block; margin-bottom:5px">
<?php if ($row_contact['contact_image']) { ?><img src="images/<?php echo $row_contact['contact_image']; ?>" width="95" height="71" class="contactimage" /><?php } ?>
<h2><?php echo $row_contact['contact_first']; ?> <?php echo $row_contact['contact_last']; ?><?php if ($row_contact['contact_company']) { ?><span style="color:#999999"> with <?php echo $row_contact['contact_company']; ?><?php } ?></span>
<?php if ($can_edit) { ?><a style="font-size:12px; font-weight:normal" href="contact.php?id=<?php echo $row_contact['contact_id']; ?>">&nbsp;&nbsp;+ Edit contact </a><?php } ?>    </h2>
<br clear="all" />
</div>

<p><br />
    </p>




<?php if (!$update) { echo "Add a new note <br>"; } ?>

<form action="" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<textarea name="note_text" style="width:95% "rows="2" id="note_text"><?php echo $row_note['note_text']; ?></textarea>
        <br />
<input type="submit" name="Submit2" value="<?php if ($update==1) { echo 'Update'; } else { echo 'Add'; } ?> note" />
</form>

      <?php if ($update==1) { ?>  <a href="delete.php?note=<?php echo $row_note['note_id']; ?>&amp;id=<?php echo $row_note['note_contact']; ?>" onclick="javascript:return confirm('Are you sure you want to delete this note?')">Delete Note</a><?php } ?>
<?php if ($totalRows_notes > 0) { ?>
        <hr />
        <?php do { ?>
<div <?php if ($row_notes['note_date'] > time()-1) { ?>id="newnote"<?php } ?>>
        <span class="datedisplay"><a href="?id=<?php echo $row_contact['contact_id']; ?>&note=<?php echo $row_notes['note_id']; ?>"><?php echo date('F d, Y', $row_notes['note_date']); ?></a></span><br />
          <?php echo $row_notes['note_text']; ?>
</div>
          <hr />
              <?php } while ($row_notes = mysql_fetch_assoc($notes)); ?>

<?php } ?>


  </div>
  <?php include('includes/right-column.php'); ?>
  
  <br clear="all" />
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
