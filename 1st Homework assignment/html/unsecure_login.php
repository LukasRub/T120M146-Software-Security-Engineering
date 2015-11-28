<?php
  $errorMessage = "";
  $dbhost = "localhost";
  $dbname = "test";
  $dbuser = "vagrant";
  $dbpass = "pass";

  if(isset($_POST['logout'])) {
    session_destroy();
  }

  if(isset($_POST['unsecure_login']))
  {
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!$conn)
    {
      $errorMessage = "Could not connect";
      die('Could not connect: ' . mysql_error());
    }

    $db_selected = mysql_select_db($dbname, $conn);
    if (!$db_selected) {
      $errorMessage = "Can't use database";
      die ('Can\'t use database : ' . mysql_error());
    }

    $query = "SELECT * FROM Users WHERE username='" . $username . "' AND
      PASSWORD = '" . $password . "' LIMIT 1";

    $result = mysql_query($query);

    if (!result) {
      $errorMessage = "Invalid query";
      die ('Invalid query : ' . mysql_error());
    }

    $row = mysql_fetch_assoc($result);
    if ($row){
      $_SESSION['user']['username'] = $row["username"];
      $_SESSION['user']["password"] = $row["password"];
      $_SESSION['user']["id"]=$row["id"];
    }
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Unsecure login</title>
  </head>
  <body>
    <form name="login" method="post" action="unsecure_login.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username">
      <label for="username">Password:</label>
      <input type="text" id="password" name="password">
      <input type="submit" id="unsecure_login" name="unsecure_login" value="Submit">
      <input type="submit" id="logout" name="logout" value="Logout" action="unsecure_login.php">
    </form>
    <div class="">
      <?php echo $errorMessage; ?>
    </div>
    <div class="">
      <?php
        if (isset($_SESSION['user'])){
          echo "Logged in as " . $_SESSION['user']['username'];
        } else echo "Not logged in";
      ?>
    </div>

  </body>
</html>
