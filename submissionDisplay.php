<?php
    $server_name="localhost";
    $username="root";
    $password="";
    $database_name ="capstone";
    $table = "";
    $id = $_GET["id"];
    $res_list = [];
    try {
    $conn = new PDO("mysql:host=$server_name; dbname=$database_name", $username, $password);
    $args[] = "SELECT * FROM ";
    if (isset($_POST["Admin"])) {
        $args[] = "admin ";
        $args[] = "WHERE AdminId=:id;";
    } 
    else if (isset($_POST["Idea"])) {
        $args[] = "idea ";
        $args[] = "WHERE IdeaID=:id;";
    }
    $query = "";
    foreach ($args as $a) {
        $query .= $a;
    }
    // echo $query;
    $result = $conn->prepare($query);
    $result->bindParam(':id', $id);
    if ($result->execute()) {
        // add submissions to list for display
        $row = $result->fetchAll();
    }
    foreach ($row as $r) {
        $res_list[] = $r;
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="adminPageStyles.css">
    <title>Display Submission</title>
</head>
<body>
    

    <header>
        <nav class="topNav"> <a class="logo" href="index.html"> 
          <img src="assets/logo/smrtAndLogoCropped.png" alt="logo" style="max-width: 150px; height: 40px; margin-left: 20px; margin-bottom: 10px;"> 
              </a>
          <div class="logout">
              <button type="button" onclick="redirectToIndexPage()">Logout</button>
          </div>
        </nav>
    </header>

    <div id="display">

    </div>

    <button type="button" onclick=prevPage() >Return to Queries</button>
    <!-- TODO: Figure out how to display this better -->
    <!-- IdeaID=${s["IdeaID"]}&IdeaSub=${s["IdeaSubmission"]}&InnovID=${s["InnovatorID"]}&CompID=${s["CompanyID"]} -->
    <script>
        function prevPage() {
            history.go(-1);
        }
        // url = window.location.search.match(/[a-zA-Z0-9]+/g);                                                // separate text by spaces; remove non alpha-num characters
        let result = <?php echo json_encode($res_list); ?>;
        let adminTable = result[0]["AdminID"];
        // TODO: display entire list of values
        if (adminTable) {
            document.getElementById("display").innerHTML = "AdminID: " + adminTable +
            "<br/>" + "Username: " + result[0]["AdminName"] + 
            "<br/>" + "Password: " + result[0]["AdminPassword"] + 
            "<br/>" + "Email: " + result[0]["AdminEmail"] + 
            "<br/>" + "Company: " + result[0]["CompanyID"];
        } else {
            document.getElementById("display").innerHTML = "IdeaID: " + result[0]["IdeaID"] +
            "<br/>" + "Submission: " + result[0]["IdeaSubmission"] +
            "<br/>" + "User: " + result[0]["InnovatorID"] +
            "<br/>" + "Company: " + result[0]["CompanyID"] +
            "<br/>" + "Date/Time Submitted: " + result[0]["IdeaID"];
        }

        
    </script>
    
</body>
</html>