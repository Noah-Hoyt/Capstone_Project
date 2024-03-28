<?php
// Database connection parameters
$server_name="localhost";
$username="root";
$password="";
$database_name ="capstone";
session_start();
if (!empty($_SESSION)) {
  session_unset();
  session_destroy();
  session_start();
}


try {
  // Create connection
  $conn = new PDO("mysql:host=$server_name; dbname=$database_name", $username, $password);

  if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // check admin credentials
    $admin_query = "SELECT CompanyID FROM admin WHERE AdminName=:user and AdminPassword=:pass;";
    $result = $conn->prepare($admin_query);
    $result->bindParam(':user', $user);
    $result->bindParam(':pass', $pass);

    // if failed to run, kill script
    if (!$result->execute()) {
      echo "Error on admin checking: " . $admin_query;
      die();
    } else {
      // if no admins match, kill script
      if ($result->rowCount() < 1) {
        echo "Error no admin found "; 
        die();
      } else {
        // create session variables
        $_SESSION['username'] = $user;
        $_SESSION['CompanyID'] = (int)$result->fetchColumn();
        $timestamp = time();
        $currentDate = gmdate('Y-m-d', $timestamp);
        $_SESSION['timeLog'] = $currentDate;

        // redirect to admin page
        header("Location: adminPage.php");
      }
    }
  }
} catch(PDOExecption $e) {
    echo "Connection Failed: " . $e->getmessage();
    die();
}
catch (Exception $ex) {
    echo "Failed: " . $ex->getmessage();
    die();
}
?>