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
    <link rel="stylesheet" type="text/css" href="../design/main.css">
    <link rel="stylesheet" type="text/css" href="../design/fonts.css">
    <link rel="stylesheet" type="text/css" href="../design/account.css">
  </head>
  <body style="display: flex; flex-direction: column; min-height: 100vh; justify-content: space-between">
  <nav class="navbar navbar-expand-lg d-flex justify-content-evenly">
      <div class="container">
        <a class="navbar-brand" href="#">
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
            <li><a class="nav-link" href="account.php" style="padding-left: 0;">Account</a></li>
            <li class="nav-item">
              <a class="nav-link" href="forum.php">Forum</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <h1 id="title" style="padding: 0; text-align: center; text-indent: 0;">Make an account</h1>
      <input type="text" name="sign_email" placeholder="Email">
      <input type="text" name="sign_username" placeholder="Username">
      <input type="password" name="sign_password" placeholder="Password">
      <button class="button">Sign up</button>
    </form>
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

    <?php
    include('functions.php');
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "poetica";
    $con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

    if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
    }
    
    if(!empty($_POST["sign_username"]) && !empty($_POST["sign_password"])){
      if(username_is_taken($con, $_POST["sign_username"])){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">This username is taken.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        
        die;
      }
      else if(!username_is_valid($_POST["sign_username"])){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">This username contains invalid characters.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        
        die;
      }

      else if(!password_is_valid($_POST["sign_password"])){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">The password is too short.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        
        die;
      }

      else{
        add_to_db($con, $_POST["sign_email"], $_POST["sign_username"],$_POST["sign_password"]);

        $_SESSION["logged_in"] = true;
        $_SESSION["username"] = $_POST["sign_username"];

        setcookie("logged_in", true, time() + (86400 * 30), "/"); // 86400 = 1 day
		    setcookie("username", $_POST["sign_username"], time() + (86400 * 30), "/");

        $_POST = array();

    }
    }

    ?>
  </body>
</html>