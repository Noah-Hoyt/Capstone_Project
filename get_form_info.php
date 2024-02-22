<?php
$server_name="localhost";
$username="root";
$password="";
$database_name ="innovationhub";

// $conn=mysqli_connect($server_name, $username, $password, $database_name);



// Check connection
try
{
    $conn = new PDO("mysql:host=$server_name; dbname=$database_name", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_POST['save']))
    {
        $name = $_POST['name'];
        $email = $_POST['email'];

        // Table name is entry_details
        // Column names are: first_name, last_name, gender, email, mobile
        $sql_query = "INSERT INTO innovator (InnovatorName, InnovatorEmail)
        VALUES (:name, :email)";

        $stmt = $conn->prepare($sql_query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);

        // Execute the query
        if($stmt->execute())
        {
            echo "Innovation Submitted Succesfully!";
        }
        else
        {
            echo "Error: " . $sql . $e->getMessage();
        }
    }
}
catch(PDOExecption $e)
{
        echo "Connection Failec: " . $e->getessage();
        die();
}
