<?php require_once('includes/config.php'); 
include('includes/functions.php');
session_start();
if (isset($_SESSION['user'])) {
header('Location: index.php');
}
mysql_select_db($database_contacts, $contacts);
$pagetitle = Login;


if ($_POST['email']  && $_POST['password']) {
record_set('logincheck',"SELECT * FROM users WHERE user_email = '".addslashes($_POST['email'])."' AND user_password = '".addslashes($_POST['password'])."'");

if ($totalRows_logincheck==1) { 
	$_SESSION['user'] = addslashes($_POST['email']);
	$redirect = 'index.php';
	header(sprintf('Location: %s', $redirect)); die;	
}

if ($totalRows_logincheck < 1) { 
redirect('Incorrect Username or Password',"login.php");
}

}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $pagetitle; ?></title>
<script src="includes/lib/prototype.js" type="text/javascript"></script>
<script src="includes/src/effects.js" type="text/javascript"></script>
<script src="includes/validation.js" type="text/javascript"></script>
<link href="includes/style.css" rel="stylesheet" type="text/css" />
<link href="includes/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="logincontainer">
  <h1>User Login </h1>
  <form id="form1" name="form1" method="post" action="">

    <?php display_msg(); ?>
Email Address <br />
    <input name="email" type="text" size="35" class="required validate-email" title="You must enter your email address." />
    <br />
    <br />
    Password<br />
    <input type="password" name="password" class="required" title="Please enter your password." />
    <br />
    <br />
    <input type="submit" name="Submit" value="Login" />
    <a href="password.php">Forget password?</a>
  </form>
					<script type="text/javascript">
						var valid2 = new Validation('form1', {useTitles:true});
					</script>
</div>
</body>
</html>