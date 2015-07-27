<?php require_once('includes/config.php'); ?><?php require_once('includes/config.php'); 
include('includes/sc-includes.php');
$pagetitle = 'Dashboard';

if (empty($_GET['s']) && isset($_GET['s'])) {
header('Location: '.$_SERVER['HTTP_REFERER']); die;
}

$cwhere = "WHERE history_status = 1";
if (isset($_GET['s'])) {
$cwhere = "WHERE history_status = 1 AND ($like_where)";
}

$search = 0;
$nwhere = "";
if (isset($_GET['s'])) {
$search = 1;
$nwhere = "WHERE note_text LIKE '%".addslashes($_GET['s'])."%' ";
}

//get notes
record_set('notes',"SELECT * FROM notes INNER JOIN contacts ON note_contact = contact_id $nwhere ORDER BY note_date DESC LIMIT 0, 20");


//get contacts
$climit = !empty($_GET['s']) ? 1000 : 10;
record_set('contactlist',"SELECT * FROM history INNER JOIN contacts ON contact_id = history_contact $cwhere ORDER BY history_date DESC LIMIT 0, $climit");

if (!$totalRows_contactlist && !isset($_GET['s'])) { header('Location: contact.php'); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $pagetitle; ?></title>
<link href="includes/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include('includes/header.php'); ?>
<div class="container">
  <div class="leftcolumn">
<?php if ($search==1) { ?>
Search results for <em><?php echo $_GET['s']; ?></em>.<br />
<br />
<?php } ?>

<?php if ($totalRows_contactlist > 0) { ?>
    <h2>Contacts</h2>
<br />
    <?php $i = 1; do { 
$comma = "";
if ($i != $totalRows_contactlist) {
$comma = ",";
}
?>
        <a href="contact-details.php?id=<?php echo $row_contactlist['contact_id']; ?>"><?php echo $row_contactlist['contact_first']; ?> <?php echo $row_contactlist['contact_last']; ?></a><?php echo $comma; ?>
      <?php $i++; } while ($row_contactlist = mysql_fetch_assoc($contactlist)); ?>
      <?php if ($totalRows_contactlist > 10) { ?>
      <a href="contacts.php">View all...</a>
      <?php } ?>
      <br />
      <br />
      <hr />
      <br />
    <?php } ?>

<?php if ($totalRows_notes > 0) { ?>
      <h2> 
      Notes  
      </h2>
<br />
      <?php $i = 1; do { ?>
<div <?php if ($row_notes['note_date'] > time()-1) { ?>id="newnote"<?php } ?>>
        <span class="datedisplay"><a href="contact-details.php?id=<?php echo $row_notes['note_contact']; ?>&note=<?php echo $row_notes['note_id']; ?>"><?php echo date('F d, Y', $row_notes['note_date']); ?></a></span> for <a href="contact-details.php?id=<?php echo $row_notes['note_contact']; ?>"><?php echo $row_notes['contact_first']; ?> <?php echo $row_notes['contact_last']; ?></a><br />
          <?php echo $row_notes['note_text']; ?>
</div>
          <?php if ($totalRows_notes!=$i) { ?><hr /><?php } ?>
              <?php $i++;  } while ($row_notes = mysql_fetch_assoc($notes)); ?>
<?php } ?>
  </div>
  <?php include('includes/right-column.php'); ?>
  <br clear="all" />
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>