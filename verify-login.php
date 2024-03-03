<?php
// Database connection parameters
$server_name="localhost";
$username="root";
$password="";
$database_name ="capstone";
$idea_list = [];
$admin_list = [];

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
                // echo "Admin Found!"

                //Get ideas
                $args[] = "SELECT * FROM idea ";                        // array to hold query
                $comp_id = (int)$result->fetchColumn();                 // company id value (int)
                
                // if not SMRT, search for company id
                if ($comp_id != 2) {
                  $args[] = "WHERE CompanyID=:companyID ";
                } 
                $args[] = "ORDER BY InnovatorID";                       // TODO: make alterable
                $query = "";
                // append args together to create query
                foreach ($args as $a) {
                  $query .= $a;
                }
                $ideas = $conn->prepare($query);
                // bind parameters if needed
                if (count($args) > 2) {
                  $ideas->bindParam(':companyID', $comp_id);
                }
                if ($ideas->execute()) {
                  // add submissions to list for display
                  $tmp_ideas = $ideas->fetchAll();
                  foreach ($tmp_ideas as $i) {
                    $idea_list[] = $i;
                  }
                }

                // Get admins
                $q = "SELECT * FROM admin";
                $ads = $conn->prepare($q);
                if ($ads->execute()) {
                  $tmp_list = $ads->fetchAll();
                  foreach ($tmp_list as $a) {
                    $admin_list[] = $a;
                  }
                }
                
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

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content= "width=device-width, initial-scale=1">
<title>Untitled Document</title>
<link rel="stylesheet" href="adminPageStyles.css">


</head>

<body>
<header>
  <nav class="topNav"> <a class="logo" href="#"> 
    <img src="assets/logo/smrtAndLogoCropped.png" alt="logo" style="max-width: 150px; height: 40px; margin-left: 20px; margin-bottom: 10px;"> 
		</a>
    <div class="logout">
        <button type="button" onclick="redirectToIndexPage()">Logout</button>
    </div>
  </nav>
</header>


    <h1> 
    <div class="banner">
        Welcome To SMRT Innovation Hub
    </div>
    
    </h1>
    
<div class="sidebar">
  <ul>
    <!-- <li><a id="accounts" href="#">Accounts</a></li>
    <li><a id="ideas" href="#">Idea Submissions</a></li> -->
    <li><button id="accounts" type="button" onclick=showAdmins()>Admins</button></li>
    <li><button id="ideas" type="button" onclick=showIdeas()>Idea Submissions</button></li>
  </ul>
</div>

<form action="submissionDisplay.php" method ="post" id="content">
  <ul>
  <!-- <?php while ($row = $ideas->fetch()) { ?>
    <li> 
      <div>Row <?php echo "ID: " . $row['IdeaID'] ." IDEA: " . $row['IdeaSubmission'].  " COMPANY: " . $row['CompanyID'] . " USER: " . $row['InnovatorID']; ?></div>
    </li>
    <br>
    <?php } ?> -->
  </ul>
  </form>
	
<script>
  const display_area = document.getElementById("content");
  function redirectToIndexPage() {
    // Redirect to the index page
    window.location.href = "index.html";
  }
  
  function logOut() {
  // Needs to both logout, display it then redirect to index.html
  }
  
  // adds admin data to content div
  function showAdmins() {
    display_area.innerHTML = "";
    let supervisors = <?php echo json_encode($admin_list); ?>;
    for (let s of supervisors) {
      // console.log(s);
      // `<div class="row_display">"\t\t\t\t</div>\n`
      // href="submissionDisplay.html?AdminID=${s["AdminID"]}&Username=${s["AdminName"]}&Password=${s["AdminPassword"]}&Email=${s["AdminEmail"]}&Company=${s["CompanyID"]}"
      display_area.innerHTML += `<div class="row_display">
      <p name="AdminID">AdminID: ${s["AdminID"]}</p>\t<p>Username: ${s["AdminName"]}</p>\t<p>Password: ${s["AdminPassword"]}</p>\t<p>Email: ${s["AdminEmail"]}</p><p>CompanyID: ${s["CompanyID"]}</p>
      <button name="Admin" type="submit" onclick=lamda:appendURL("${s["AdminID"]}")>View</button></div>\n`
    }
  }

  //adds idea data to content div
  function showIdeas() {
    display_area.innerHTML = "";

    let subs = <?php echo json_encode($idea_list); ?>;
    for (let s of subs) {
      // console.log(s);
      display_area.innerHTML += `<div class="row_display">
      <p name="IdeaID">IdeaID: ${s["IdeaID"]}</p>\t<p>Idea: ${s["IdeaSubmission"]}</p>\t<p>InnovID: ${s["InnovatorID"]}</p>\t<p>CompID: ${s["CompanyID"]}</p>
      <button name="Idea" type="submit" onclick=lamda:appendURL("${s["IdeaID"]}")>View</button></div>\n`
    }
  }

  function appendURL(id) {
    display_area.action += "?id=" + id;
  }
  
  // display buttons to different views if user is SMRT admin
  let tmp = <?php echo $comp_id?>;
  if (parseInt(tmp) == 2) {
    console.log("here");
    document.getElementById("accounts").style.display = "block";
    document.getElementById("ideas").style.display = "block";
  } 

  showIdeas();
</script>
<style> 
  #content {
    background-color: teal;
    border: 4px solid ;
    padding: 5px;
    margin: 4px;
  }
  .row_display {
    color: #000;
    padding: 7px 0 7px 4px;
    margin: 5px;
    border: 2px solid;
  }

  .row_display a {
    text-decoration: none;
    color:#fff;
  }

  .row_display p {
    display: inline;
    border-color: #fff;
    padding: 3px;
    margin 3px;
  }
</style>
</body>
</html>

