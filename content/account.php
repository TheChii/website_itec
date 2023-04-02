<?php
  session_start();
  if(!$_SESSION["logged_in"]){
    header("Location: login.php");
    die();
  }

  if(!$_SESSION['username']) echo "no username";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" type="text/css" href="../design/main.css">
    <link rel="stylesheet" type="text/css" href="../design/fonts.css">
    <link rel="stylesheet" type="text/css" href="../design/forum.css">
    <link rel="stylesheet" type="text/css" href="../design/account.css">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg d-flex justify-content-evenly">
      <div class="container">
        <a class="navbar-brand" href="../index.php">
          Poet<i class="fa-solid fa-pencil fa-bounce"></i>ca</a
        >
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
        <i class="fa-solid fa-bars navbar-toggler-icon"></i>
          
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
            </li>
            <li><a class="nav-link" href="account.php">Account</a></li>
            <li class="nav-item">
              <a class="nav-link" href="forum.php">Forum</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <section>
      <div id="photo">
        <i class="bi bi-person-fill"></i>
        <h1><?php echo $_SESSION["username"]; ?></h1>

      </div>
      <span id="signout"><a href="logout.php">Sign Out</a></span>
      <div id="content">
        <h1>Archive:</h1>
        <div id="archive">
          <?php
            require ("functions.php");
            $dbhost = "localhost";
            $dbuser = "root";
            $dbpass = "";
            $dbname = "poetica";
            $con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

            $usrname = $_SESSION["username"];

            if(!$usrname) echo"failed";

            $query = "SELECT * FROM poems WHERE author = '$usrname' ORDER BY id DESC";
            $result = $con->query($query);
        
            if($result -> num_rows > 0){
              while($row = $result->fetch_assoc()) {
                //echo $row['content'] . "<br>";
                $json = json_decode($row['img'], true);
                $new_url = $json["data"][0]["url"];
                $card_theme = $row['theme'];
                if($card_theme == "any") $card_theme = "diverse";
                $card_content = str_replace("None<br>", "", $row['content']);
                
                echo '<div class="card" style="width: 18rem;"> <img src='.$new_url.' class="card-img-top" alt="an image related to the poem"> <div class="card-body"><h5 class="card-title">'.$card_theme.'</h5><p class="card-text">'.$card_content.'</p><a href="#" class="btn btn-primary">Read Poem</a><i class="bi bi-trash-fill"></i></div></div>';
              }
            }
            else{
              echo "you haven't saved any poems yet.";
            }   
            
          ?>
          <!--
          <div class="card" style="width: 18rem;">
            <img src="../images/copac.jpeg" class="card-img-top" alt="an image related to the poem">
            <div class="card-body">
              <h5 class="card-title">Poem title</h5>
              <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus metus tellus, feugiat id dui vitae, suscipit ultricies erat. Morbi et ultricies dolor, quis imperdiet nulla. Fusce efficitur, justo a luctus blandit, ipsum eros tristique risus, et sodales ligula eros quis elit. Fusce porta sem quis metus vehicula placerat. Nulla sed augue non odio maximus ullamcorper quis nec mi. Nam et tortor in mauris volutpat semper id et lorem. Quisque scelerisque metus sed consequat iaculis. Nam aliquam sem eget malesuada cursus. Integer non urna elit. Cras dictum commodo mauris eu convallis. Suspendisse eu pharetra tellus.</p>
              <a href="#" class="btn btn-primary" onclick="myFunction()">Read Poem</a>
              <i class="bi bi-trash-fill"></i>
            </div>
          </div>
          <div class="card" style="width: 18rem;">
            <img src="../images/copac.jpeg" class="card-img-top" alt="an image related to the poem">
              <div class="card-body">
              <h5 class="card-title">Poem title</h5>
              <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus metus tellus, feugiat id dui vitae, suscipit ultricies erat. Morbi et ultricies dolor, quis imperdiet nulla. Fusce efficitur, justo a luctus blandit, ipsum eros tristique risus, et sodales ligula eros quis elit. Fusce porta sem quis metus vehicula placerat. Nulla sed augue non odio maximus ullamcorper quis nec mi. Nam et tortor in mauris volutpat semper id et lorem. Quisque scelerisque metus sed consequat iaculis. Nam aliquam sem eget malesuada cursus. Integer non urna elit. Cras dictum commodo mauris eu convallis. Suspendisse eu pharetra tellus.</p>
              <a href="#" class="btn btn-primary">Read Poem</a>
              <i class="bi bi-trash-fill"></i>
            </div>
          </div>
          -->
          </div>
        </div>
        <h1>Your Posts:</h1>
          <div id="posts">
            
          <?php

            $usrname = $_SESSION["username"];

            if(!$usrname) echo"failed";

            $query = "SELECT * FROM poems WHERE author = '$usrname' AND published = 1 ORDER BY id DESC";
            $result = $con->query($query);
        
            if($result -> num_rows > 0){
              while($row = $result->fetch_assoc()) {
                //echo $row['content'] . "<br>";
                $json = json_decode($row['img'], true);
                $new_url = $json["data"][0]["url"];
                $card_theme = $row['theme'];
                if($card_theme == "any") $card_theme = "diverse";
                $card_content = str_replace("None<br>", "", $row['content']);
                
                echo '<div class="card" style="width: 18rem;"> <img src='.$new_url.' class="card-img-top" alt="an image related to the poem"> <div class="card-body"><h5 class="card-title">'.$card_theme.'</h5><p class="card-text">'.$card_content.'</p><a href="#" class="btn btn-primary">Read Poem</a><i class="bi bi-trash-fill"></i></div></div>';
              }
            }
            else{
              echo "you haven't saved any poems yet.";
            }   
            
          ?>
        
     
          </div>
      </div>
    </section>
    <div id="myPopup" style="background-color: #2a2827;
  position: fixed;
  top: 50px;
  bottom: 50px;
  right: 30px;
  left: 30px;
  border-radius: 40px;
  opacity: 0.8; display: none; color:white; text-align: center; padding: 0; margin:0; padding-top: 10px; font-size: 20px; font-family: 'Playfair Display', serif; letter-spacing: 0.5px;">
    <p id="myPoem" style="margin-top: 30px">
  This is where the poem will be displayed</p>
      <span id="close" style="position: absolute; right: 50px; top: 15px; font-size: 40px;">x</span>
    </div>
    <footer>
      <div id="about">
        <h1>About Us</h1>
        <p>Welcome to our poetry generator site, where words come to life and emotions are expressed through verse! Our website is committed to giving you a dynamic and imaginative space to discover the beauty of poetry.
With our advanced algorithm, you can generate unique and personalized poems in seconds. Whether you are looking to express your feelings, commemorate a special occasion, or simply enjoy the art of poetry, our generator has something for everyone.</p>
      </div>
      <div id="dev">
        <p>Back-end developer: Chiriac Theodor</p>
        <p>Front-end developer: Pirvanescu Marian</p>
        <p>Web designer: Ungureanu Alexandra</p>
      </div>
    </footer>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>
    <script src="https://kit.fontawesome.com/74d2e4807b.js" crossorigin="anonymous"></script>
    <script>
      function myFunction(){
          popup.style.display = "block";
        }
        function ourfunction(){
          popup.style.display = "none";
        }
        var click = document.getElementById("click");
        var popup = document.getElementById("myPopup");   
        click.addEventListener("click", myFunction);
        var close=document.getElementById("close");
        close.addEventListener("click", ourfunction); 
    </script>
  </body>
</html>
