<?php
session_start();
require ("functions.php");
require __DIR__ . '/vendor/autoload.php';
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "poetica";
$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
use Orhanerday\OpenAi\OpenAi;
if ($con->connect_error) {
die("Connection failed: " . $con->connect_error);
}

if(isset($_POST['savepoem'])){
	if(isset($_SESSION["last_poem"]) && isset($_SESSION["logged_in"])){
		if(add_poem_to_db($con, $_SESSION['last_poem'], $_SESSION['last_keywords'], $_SESSION['last_theme'], $_SESSION['username'], $_SESSION["last_image"], $_SESSION["last_rhyme"], 0)){
		} else {
			echo "<br> something went wrong saving <br>";
			var_dump($con);
		}
		unset($_POST["savepoem"]);	
	}
	else{
		echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"> You need an account in oreder to do that!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
	}

}

if(isset($_POST['publishpoem'])){
	if(isset($_SESSION["last_poem"]) && isset($_SESSION["logged_in"])){
		if(add_poem_to_db($con, $_SESSION['last_poem'], $_SESSION['last_keywords'], $_SESSION['last_theme'], $_SESSION['username'], $_SESSION["last_image"], $_SESSION["last_rhyme"], 1)){
		} else {
			echo "<br> something went wrong saving <br>";
			var_dump($con);
		}
		unset($_POST["publishpoem"]);	
	}
	else{
		echo "??";
	}
}

if(isset($_POST['submit'])){
	
	$dom = new DOMDocument('1.0', 'utf-8');
	
	$api_key = "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";
	$open_ai = new OpenAi($api_key);
	
	if(empty($_POST['keywords'])){
		$_POST["keywords"] = "none";
		$_SESSION["last_keywords"] = "none";
	} else {
		$_POST["keywords"] = censor($_POST["keywords"]);
		$_SESSION["last_keywords"] = format_keywords($_POST["keywords"]);
	}
	
	if(empty($_POST['theme'])){
		$_POST['theme'] = "any";
		$_SESSION["last_theme"] = "any";
	} else {
		$_POST['theme'] = censor($_POST['theme']);
		$_SESSION['last_theme'] = $_POST['theme'];

	}
	
	if(empty($_POST['rhyme'])){
		$_POST['rhyme'] = "any";
		$_SESSION["last_rhyme"] = "any";
	} else $_SESSION['last_rhyme'] = $_POST['rhyme'];
	
	if(empty($_POST['verses'])){
		$_POST["verses"] = rand(2,4);
	}
	
	if(empty($_POST['lines'])){
		$_POST["lines"] = rand(2,5);
	}
	
	if(empty($_POST['lines'])){
		$_POST["lines"] = rand(2,5);
	}
	
	$prompt = return_command(format_keywords($_POST["keywords"]), $_POST["verses"], $_POST["lines"], $_POST["rhyme"], $_POST['theme']);
	$_POST['prompt'] = $prompt;
	
	$_SESSION["keywords"] = format_keywords($_POST["keywords"]);
	$_SESSION["theme"] = $_POST["theme"];
	$complete = $open_ai->completion([
		'model' => 'text-davinci-003',
		'prompt' => $prompt,
		'temperature' => 0.9,
		'max_tokens' => 256,
		'top_p' => 1,
		'frequency_penalty' => 0,
		'presence_penalty' => 0
	]);
	
	$selected_test = get_poem($complete);
	//echo $p . "<br>";
	$poem = poem_format($selected_test);
	
	$_SESSION["last_poem"] = $poem;
	
	$im_kw = $_POST["keywords"];
	if($_POST["keywords"] == "none" || $_POST["keywords"] == "any") {
		$im_kw = "poetry";
	}
	
	$params = [
		"model" => "image-alpha-001",
		"prompt" => $im_kw,
		"response_format" => "url",
		"size" => "512x512"
	];
	
	// Build the API request
	$url = "https://api.openai.com/v1/images/generations";
	$data = json_encode($params);
	$headers = [
		"Content-Type: application/json",
		"Authorization: Bearer " . $api_key
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	// Send the API request and get the response
	$response = curl_exec($ch);
	curl_close($ch);
	
	// Parse the response JSON to get the image URL
	$json = json_decode($response, true);
	$image_url = $json["data"][0]["url"];
	$_SESSION["imtoshow"] = $json["data"][0]["url"];
	$_SESSION["last_image"] = $response;

	unset($_POST["submit"]);
	
}

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
    <link rel="stylesheet" type="text/css" href="design/main.css">
    <link rel="stylesheet" type="text/css" href="design/fonts.css">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg d-flex justify-content-evenly">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          Poet<i class="fa-solid fa-pencil fa-bounce"></i>ca</a>
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
              <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li><a class="nav-link" href="content/account.php" style="padding-left: 0;">Account</a></li>
            <li class="nav-item">
              <a class="nav-link" href="content/forum.php">Forum</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <cite>
      <h1><span style="font-size: 65px;">Poetica</span> <br /> Unleash Your Inner Poet with Our Poetry Generator</h1>
    </cite>

    <div id="forms">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
		<div class="text">
		<h6>Keywords:</h6>
		<input type="text" name="keywords" placeholder="write...">
		<p style="display: block; float: right;">max. 5 words</p>
		</div>
		<div class="text">
		<h6>Verses:</h6>
		<input type="number" name="verses" placeholder="write...">
		<p style="display: block; float: right; padding: 0; margin: 0;">max. 5 words</p>
		</div>
		<div class="text">
		<h6>Lines:</h6>
		<input type="number" name="lines" placeholder="write...">
		<p style="display: block; float: right;">max. 5 words</p>
		</div>
		<div class="text">
		<h6>Theme:</h6>
		<input type="text" name="theme" placeholder="write...">
		<p style="display: block; text-align: right;">one word only</p>
		</div>
	
		<div class="text">
		<h6>Type of rhyme:</h6>
		<select>
			<option>Monorhyme</option>
			<option>Alternate rhyme</option>
			<option>Coupled rhyme</option>
			<option>Enclosed rhyme</option>
			<option>Simple four line</option>
		</select>
		</div>
        <input id="generate" type='submit' value="Generate" name='submit'>
      </form>
    </div>																																						
	<div id="read">
	<?php echo "<img src='" . $_SESSION["imtoshow"] . "'>";?>
      <span id="poem">
	  <?php echo $_SESSION["last_poem"];?>;
		<div id="buttons">
		<div id="posting">
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
		  <input id="save" type='submit' value="Save" name='savepoem'>
		  <input id="post" name = "publishpoem" type ="submit" value="Publish">	
		  
		</form>																																																																									</form>
		</div>																							
      </div>
	
	</span>
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

  <?php
	
	
?>
    
  </body>
</html>
