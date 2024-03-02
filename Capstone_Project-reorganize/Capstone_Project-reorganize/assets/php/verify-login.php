<?php
// Database connection parameters
$server_name="localhost";
$username="root";
$password="";
$database_name ="capstone";

try {
    // Create connection
    $conn = new PDO("mysql:host=$server_name; dbname=$database_name", $username, $password);

    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $admin_query = "SELECT CompanyID FROM admin WHERE AdminName=:user and AdminPassword=:pass;";
        $result = $conn->prepare($admin_query);
        $result->bindParam(':user', $user);
        $result->bindParam(':pass', $pass);
        if (!$result->execute()) {
            echo "Error on admin checking: " . $admin_query;
        } else {
            if ($result->rowCount() < 1) {
                echo "Error no admin found "; 
            } else {
                echo "Admin Found!";
                header('Location: adminPage.html');
            }
        }
    }
    // die();

} catch(PDOExecption $e) {
    echo "Connection Failed: " . $e->getmessage();
    die();
}
catch (Exception $ex) {
    echo "Failed: " . $ex->getmessage();
    die();
}

?>
