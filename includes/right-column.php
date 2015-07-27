<link href="includes/layout.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_setTextOfTextfield(objName,x,newText) { //v3.0
  var obj = MM_findObj(objName); if (obj) obj.value = newText;
}
//-->
</script>
<div class="rightcolumn">
<?php if ($contactcount > 0) { ?><br />

<?php if ($totalRows_tags) { ?>
Tags:
<?php 
$i = 1;
$comma = ",";
do { 
if ($i==$totalRows_tags) {
$comma = "";
}
?>
<a href="index.php?s=<?php echo $row_tags['tag_description']; ?>"><?php echo $row_tags['tag_description'].$comma; ?></a>
<?php 
$i++;
} while ($row_tags = mysql_fetch_assoc($tags)); ?>
<br />
<br />
<?php } ?>

<form id="form3" name="form3" method="GET" action="index.php" enctype="multipart/form-data">
      <input name="s" type="text" id="s" onfocus="MM_setTextOfTextfield('s','','')" value="Search" size="15" />
        <input type="submit" name="Submit_search" value="Go" />
  </form>
<?php } ?>
  <p><br />
    <a class="addcontact" href="contact.php" <?php if ($pagetitle == 'ContactDetails') { ?>style="border-bottom:0px"<?php } ?>>Add Contact</a>  

<?php if ($user_admin && $pagetitle != 'ContactDetails') { ?>
<a class="addcontact" href="import.php">Import/Export </a>
<a class="addcontact" href="fields.php" style="border-bottom:0px">Custom Fields </a>
<?php } ?>

</p>

    <?php if ($pagetitle == 'ContactDetails') { ?>
    <hr />
    <p><strong>Contact Information</strong><br />
      <?php if ($row_contact['contact_company']) { echo $row_contact['contact_company'] ."<br>"; } ?>
      <?php if ($row_contact['contact_street']) { echo $row_contact['contact_street']  ."<br>"; } ?>
    <?php if ($row_contact['contact_city']) { echo $row_contact['contact_city'] .","; } ?> <?php if ($row_contact['contact_state']) { echo $row_contact['contact_state']; } ?> <?php if ($row_contact['contact_zip']) { echo $row_contact['contact_zip']; } ?><?php if ($row_contact['contact_country']) { echo "<br>".$row_contact['contact_country']; } ?></p>
    <?php if ($row_contact['contact_street'] && $row_contact['contact_city'] && $row_contact['contact_state']) { ?><p><a href="http://maps.google.com/maps?f=q&amp;hl=en&amp;q=<?php echo $row_contact['contact_street']; ?>,+<?php echo $row_contact['contact_city']; ?>,+<?php echo $row_contact['contact_state']; ?>+<?php echo $row_contact['contact_zip']; ?>&gt;" target="_blank">+ View Map </a></p>
    <?php } ?>
    <hr />
    <p>      <?php if ($row_contact['contact_phone']) { ?>Phone: <?php echo $row_contact['contact_phone']; ?><br /><?php } ?>

<?php if ($row_contact['contact_web']) { ?>
      <a href="<?php echo $row_contact['contact_web']; ?>" target="_blank"><?php echo $row_contact['contact_web']; ?></a>        
<?php } ?>

<?php if ($row_contact['contact_email']) { ?>
      <a href="mailto:<?php echo $row_contact['contact_email']; ?>"><?php echo $row_contact['contact_email']; ?></a>        
<?php } ?>

</p>
<?php if ($row_contact['contact_profile']) { ?>   
 <hr />
  <strong>Background</strong><br />
  <?php echo $row_contact['contact_profile']; ?>
<?php } ?>


<?php 

//additional fields
record_set('additional',"SELECT * FROM fields INNER JOIN fields_assoc ON cfield_field = field_id WHERE cfield_contact = ".$row_contact['contact_id']." AND cfield_value IS NOT NULL AND cfield_value != ''");

if ($totalRows_additional) { ?>
<hr />
<strong>Additional Information</strong>
<br />
<?php do { ?>
<?php echo $row_additional['field_title'].": ".$row_additional['cfield_value']; ?><br />
<?php } while ($row_additional = mysql_fetch_assoc($additional)); ?>

<?php } ?>

<?php } ?>  </div>