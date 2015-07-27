<?php
error_reporting(0);

//mysql connection information

$hostname_contacts = "localhost";  
$database_contacts = "contact"; //The name of the database
$username_contacts = "root"; //The username for the database
$password_contacts = ""; // The password for the database
$contacts = mysql_connect($hostname_contacts, $username_contacts, $password_contacts) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_contacts, $contacts);

//

?>