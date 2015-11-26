<?php
  $username = $password = "yadda";
  if($_POST['username'])
  {
       $username = $_POST['username'];
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Register</title>
  </head>
  <body>
    <form name="login" method="post" action="register.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username">
      <label for="username">Password:</label>
      <input type="text" id="password" name="password">
      <input type="submit">
    </form>
    <div class="">
      <?php
      echo $username;
      echo $password;
      ?>
    </div>
  </body>
</html>
<?php
  $dbhost = "localhost";
  $dbuser = "vagrant";
  $dbpass = "pass";

  $conn = mysql_connect($dbhost, $dbuser, $dbpass);
  if(! $conn )
  {
    die('Could not connect: ' . mysql_error());
  }
?>
