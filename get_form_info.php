<?php
$server_name="localhost";
$username="root";
$password="";
$database_name ="capstone";

// Check connection
try {
    $conn = new PDO("mysql:host=$server_name; dbname=$database_name", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $company = $_POST['company'];
        $idea = $_POST['idea'];

        $check_innovator = "SELECT InnovatorID FROM innovator WHERE InnovatorName=:name AND InnovatorEmail=:email";
        $innovator_query = $conn->prepare($check_innovator);
        $innovator_query->bindParam(':name', $name);
        $innovator_query->bindParam(':email', $email);
        $innovator_id = NULL;
        if (!$innovator_query->execute()) {
            echo "Error on innovator table: " . $innovator_query . $e->getMessage();
        } else {
            // if no entry on table, make new entry
            if ($innovator_query->rowCount() < 1) {
                $new_innovator = "INSERT INTO innovator (InnovatorName, InnovatorEmail) VALUES (:name, :email)";
                $insert = $conn->prepare($new_innovator);
                $insert->bindParam(':name', $name);
                $insert->bindParam(':email',$email);
                if (!$insert->execute()) {
                    echo "Error on innovator insertion: " . $check_innovator;
                } else {
                    $innovator_query->execute();
                }
            }
            $innovator_id = $innovator_query->fetchColumn();
        }

        $check_company = "SELECT CompanyID FROM company WHERE CompanyName=:company";
        $company_query = $conn->prepare($check_company);
        $company_query->bindParam(':company', $company);
        $company_id = NULL;
        if (!$company_query->execute()) {
            echo "Error on company table: " . $check_company;
        } else {
            $company_id = $company_query->fetchColumn();
        }
        
        // Note that IdeaDT gets value of the date and time of when user submits idea formatted into UTC timezone
        // and CST is 6 hours behind UTC
        // TODO : make function or SQL query to convert IdeaDT entry data into CST timezone when displayed on page
        $submit = "INSERT INTO idea (IdeaSubmission, IdeaDT, InnovatorID, CompanyID)
        VALUES (:idea, UTC_TIMESTAMP(), :innovator_id, :company_id)";

        $stmt = $conn->prepare($submit);
        $stmt->bindParam(':idea', $idea);
        $stmt->bindParam(':innovator_id', $innovator_id);
        $stmt->bindParam(':company_id', $company_id);
        if($stmt->execute()) {
            echo "Innovation Submitted Succesfully!";
        } else {
            echo "Error: " . $submit . $e->getMessage();
            
        }
    }
} catch(PDOExecption $e) {
        echo "Connection Failed: " . $e->getmessage();
        die();
} catch (Exception $ex) {
        echo "Failed: " . $ex->getmessage();
        die();
}
