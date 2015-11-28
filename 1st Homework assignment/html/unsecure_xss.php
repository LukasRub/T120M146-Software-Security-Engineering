<?php
  session_start();
  $errorMessage = "";
  $dbhost = "localhost";
  $dbname = "test";
  $dbuser = "vagrant";
  $dbpass = "pass";

  if(isset($_POST['comment'])) {
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    $author = $_SESSION['user']['username'];
    $comment = $_POST['comment_text'];
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
    $query = "INSERT INTO Comments (author, comment, datetime) VALUES ('".
      $author . "', '" . $comment . "', NOW())";
    $result = mysql_query($query);
    if (!$result) {
      $errorMessage = "Invalid query";
      die ('Invalid query : ' . mysql_error());
    }
  }

  if(isset($_POST['logout'])) {
    session_destroy();
    $_SESSION = null;
  }

  if(isset($_POST['unsecure_login'])) {
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

    if (!$result) {
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

  $conn = mysql_connect($dbhost, $dbuser, $dbpass);
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
  $commentsQuery = "SELECT * FROM Comments";
  $commentsResult = mysql_query($commentsQuery);
  if (!$commentsResult) {
    $errorMessage = "Invalid query";
    die ('Invalid query: ' . mysql_error());
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Unsecure xss</title>
  </head>
  <body>
    <form name="login" method="post" action="unsecure_xss.php">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username">
      <label for="username">Password:</label>
      <input type="text" id="password" name="password">
      <input type="submit" id="unsecure_login" name="unsecure_login" value="Login">
      <input type="submit" id="logout" name="logout" value="Logout" action="unsecure_xss.php">
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
    <form name="comment" method="post" action="unsecure_xss.php">
      <textarea name="comment_text" id="comment_text">Enter text here...</textarea>
      <input type="submit" id="comment" name="comment" value="Comment" action="unsecure_xss.php">
    </form>
    <div class="Comments">
      <ol>
        <?php
          while ($commentsRow = mysql_fetch_assoc($commentsResult)) {
            echo "<li><span>" . $commentsRow["comment"] . "</span> <strong>" .
              $commentsRow["author"] . "</strong> <i>" . $commentsRow["datetime"]
              . "</i></li>";
          }
        ?>
      </ol>
    </div>
  </body>
</html>
