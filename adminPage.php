<?php session_start(); ?>
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
    <li><a href="#">Accounts</a>
	
	</li>
	
    <li><a href="#">Idea Submissions</a>
	
	</li>
	
    <li><a href="#">Link 3</a>
	
	</li>
	
	<li><a href="#">Link 4</a>
	
	</li>
	
  </ul>
</div>
<!-- <div class="grid-container" id="gridContainer">
  </div>
</div> -->

<?php
// Database connection parameters
$server_name="localhost";
$username="root";
$password="";
$database_name ="capstone";

try {
  // Create connection
  $conn = new PDO("mysql:host=$server_name; dbname=$database_name", $username, $password);

  $query = "SELECT * FROM ideas ORDER BY InnovatorID";
  $res = $conn->prepare()

} catch(PDOExecption $e) {
  echo "Connection Failed: " . $e->getmessage();
  die();
}
catch (Exception $ex) {
  echo "Failed: " . $ex->getmessage();
  die();
}
?>
<div>
  some text
</div>
	
    

    

<script>
  function redirectToIndexPage() {
    // Redirect to the index page
    window.location.href = "index.html";
  }
  
  function logOut() {
  // Needs to both logout, display it then redirect to index.html
  }
  
  
// Build more Javascript here to display database elements in the form of 'cards'
// const gridContainer = document.getElementById('gridContainer');

// const cardsData = [
//   { title: 'Card 1', description: 'Description of Card 1' },
//   { title: 'Card 2', description: 'Description of Card 2' },
//   { title: 'Card 3', description: 'Description of Card 3' },
// ];

// cardsData.forEach(card => {
//   const cardElement = document.createElement('div');
//   cardElement.classList.add('card');
//   cardElement.innerHTML = `
//     <h3>${card.title}</h3>
//     <p>${card.description}</p>
//   `;
//   gridContainer.appendChild(cardElement);
// });
</script>

<?php 
  echo "HERE";
?>
    
</body>
</html>