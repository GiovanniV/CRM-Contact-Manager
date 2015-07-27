<?php require_once('includes/config.php');
include('includes/sc-includes.php');

//get contacts
record_set('contactlist',"SELECT * FROM contacts");

//get custom fields
record_set('fields',"SELECT * FROM fields ORDER BY field_title ASC");

$csv_fields = array();
$csv_fields[0] = 'id';
$csv_fields[1] = 'First Name';
$csv_fields[2] = 'Last Name';
$csv_fields[3] = 'Title';
$csv_fields[4] = 'Company';
$csv_fields[5] = 'Street';
$csv_fields[6] = 'City';
$csv_fields[7] = 'State';
$csv_fields[8] = 'Zip';
$csv_fields[9] = 'Country';
$csv_fields[10] = 'Email';
$csv_fields[11] = 'Phone';
$csv_fields[12] = 'Cell';
$csv_fields[13] = 'Website';
$csv_fields[14] = 'Profile';

$i = 15;
if ($totalRows_fields) do {
$csv_fields[$i] = $row_fields[field_title];
$i++;
} while ($row_fields = mysql_fetch_assoc($fields));

foreach ($csv_fields as $key => $value) {
$csv_output .= "\"".$value."\",";
}

$csv_output .= "\n"; 


do {
$csv_output_r = array();
$csv_output_r[0] = $row_contactlist['contact_id'];
$csv_output_r[1] = $row_contactlist['contact_first'];
$csv_output_r[2] = $row_contactlist['contact_last'];
$csv_output_r[3] = $row_contactlist['contact_title'];
$csv_output_r[4] = $row_contactlist['contact_company'];
$csv_output_r[5] = $row_contactlist['contact_street'];
$csv_output_r[6] = $row_contactlist['contact_city'];
$csv_output_r[7] = $row_contactlist['contact_state'];
$csv_output_r[8] = $row_contactlist['contact_zip'];
$csv_output_r[9] = $row_contactlist['contact_country'];
$csv_output_r[10] = $row_contactlist['contact_email'];
$csv_output_r[11] = $row_contactlist['contact_phone'];
$csv_output_r[12] = $row_contactlist['contact_fax'];
$csv_output_r[13] = $row_contactlist['contact_web'];
$csv_output_r[14] = $row_contactlist['contact_profile'];

//get custom fields for this contact
record_set('lfields',"SELECT * FROM fields ORDER BY field_title ASC");

$i = 15; 
if ($totalRows_lfields) do {

record_set('cf',"SELECT * FROM fields_assoc WHERE cfield_contact = ".$row_contactlist['contact_id']." AND cfield_field = ".$row_lfields['field_id']."");

$csv_output_r[$i] = $row_cf['cfield_value'];

$i++; } while ($row_lfields = mysql_fetch_assoc($lfields));
//

foreach ($csv_output_r as $key => $value) {
$csv_output .= "\"".$value."\",";
}

$csv_output .= "\n";

} while($row_contactlist = mysql_fetch_array($contactlist));

  //You cannot have the breaks in the same feed as the content. 
  header("Content-type: application/vnd.ms-excel");
  header("Content-disposition: csv; filename=contact.csv");
  print $csv_output;
  exit;
?>

