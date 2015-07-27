<?php require_once('includes/config.php'); 
include('includes/sc-includes.php');
$pagetitle = 'Profile';

//get user information
record_set('profile',"SELECT * FROM users WHERE user_id = ".$userid."");
if (!$totalRows_profile) die;

//UPDATE PROFILE
if (!empty($_POST['email'])) {
	$password = $row_profile['user_password'];
	
	if ($_POST['password']) {
	$password = addslashes($_POST['password']);
	}
	
	mysql_query("UPDATE users SET 
		user_email = '".trim(addslashes($_POST['email']))."', 
		user_password = '".trim(addslashes($password))."', 
		user_home = '".trim(addslashes($_POST['home']))."'
	WHERE user_id = ".$userid."
");
	set_msg('Profile Updated');
	$_SESSION['user'] = addslashes($_POST['email']);

	redirect('Your changes have been saved.','profile.php');
}
//
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Update Profile</title>
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
    <h2>Profile</h2>
    <?php display_msg(); ?>    <form id="form1" name="form1" method="post" action="">
      <p>Email
        <br />
        <input name="email" type="text" id="email" value="<?php echo $row_profile['user_email']; ?>" class="required validate-email" size="35" />
      </p>
      <p><br />
        Password (leave blank to keep current password) <br />
        <input name="password" type="password" id="password" />
          <br />
      </p>
      <p>
        <input name="Submit2" type="submit" id="Submit2" value="Update" /> 
      </p>
      </form>
    
<script type="text/javascript">
	var valid2 = new Validation('form1', {useTitles:true});
</script>

    <p>&nbsp;</p>
  </div>
  <?php include('includes/right-column.php'); ?>
  <br clear="all" />
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
