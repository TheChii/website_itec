<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
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
            <li><a class="nav-link" href="profile.php" style="padding-left: 0;">Account</a></li>
            <li class="nav-item">
              <a class="nav-link" href="forum.php">Forum</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    <h1 id="title" style="padding: 0; text-align: center; text-indent: 0;">Log in</h1>
      <input type="text" name="login_username" placeholder="Email">
      <input type="password" name="login_password" placeholder="Password">
      <input class="button" name='submit' type="submit" value="log in"  >
      <a href="signup.php" id="sign">Sign up!</a>
    </form>
    <footer style="align-self: flex-end;">
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

    if(!empty($_POST["login_username"]) && !empty($_POST["login_password"])){
      
      if(!username_is_taken($con, $_POST["login_username"])){
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">This account does not exist.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        die;
      }
      //echo $_POST["login_password"] . "   vs    " . $_POST["login_username"];
      if(is_password_correct($con, $_POST["login_username"], $_POST["login_password"])){
        $_SESSION["logged_in"] = true;
				$_SESSION["username"] = $_POST["login_username"];

				unset($_POST["login_password"]);
				unset($_POST["login_username"]);

				header("Location: ../index.php");
        $_POST = array();
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"> You need an account in oreder to do that!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        die();
      } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Username or password wrong<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
      }
    } 

    ?>
</body>
</html>





