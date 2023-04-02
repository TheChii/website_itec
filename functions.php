<?php
	function queryget($con, $query)
	{
		return $con -> query($query) -> fetch_array()[0];
	}
	function username_is_taken($con, $checked_username)
	{
		$sql_code = "SELECT * from users WHERE username = '$checked_username'";
		$result = mysqli_query($con, $sql_code);

     	return mysqli_num_rows($result) ?  true : false;
	}

	function username_is_valid($username)
	{
		$max_len = 30;
		$min_len = 3;

		if(!is_string($username)) return false;

		$valid = ctype_alnum($username) ? true : false;
		$valid = (strlen($username) < $max_len && strlen($username) > $min_len) ? true : false;

		return $valid;
	}

	function password_by_username($con, $username){
		$query = "SELECT passwrd FROM users WHERE username='$username'";
		return queryget($con, $query);
		
	}

	function password_is_valid($passwrd)
	{
		$min_len = 4;
		return strlen($passwrd) < $min_len ? false : true;
	}
	function add_to_db($con, $email, $username, $passwrd)
	{
		$query = "SELECT MAX(id) FROM users;";
		$new_id = queryget($con, $query) + 1;

		$sql_code = "INSERT INTO users(id, username, passwrd, email) VALUES ('$new_id', '$username', '$passwrd', '$email');";
		return mysqli_query($con, $sql_code) ? true : false;
	}

	function username_by_id($con, $id)
	{
		$query = "SELECT username FROM users WHERE id='$id';";
		$raw_result = $con -> query($query);
		$result = $raw_result -> fetch_array()[0];
		return $result;
	}

	function id_by_username($con, $username)
	{
		$query = "SELECT id FROM users WHERE username='$username';";
		$raw_result = $con -> query($query);
		$result = $raw_result -> fetch_array()[0];
		return $result;
	}

	function is_password_correct($con, $username, $passwrd)
	{
		$query = "SELECT passwrd FROM users WHERE username='$username';";
		$raw_result = $con -> query($query);
		$result = $raw_result -> fetch_array()[0];

		return $passwrd == $result ? true : false;	
	}


	function poem_format($raw){
		//$pattern = '/\\\nVerse \d+\\\n/';	
		//$raw = preg_replace($pattern, '', $raw);
		return str_replace("\\n", "<br>", $raw);
		
	}

	function return_command($keywords, $verses, $lines, $rhyme, $theme){
		$to_return =  "a poem with " . $rhyme . " rhyme, " . $verses . " verses, ". $lines . " lines and " . " keywords: " . $keywords . ", theme: ". $theme . ", only poem no verse count no title";
		
		return $to_return;
	}

	function format_keywords($keywords){
		$to_replace = array(",", ".", "and");
		$to_return = str_ireplace($to_replace, " ", $keywords);

		return $to_return;
		
	}

	function get_poem($raw){
		$starting_index = strpos($raw, "[{") + strlen('[{"text":",');
		while($raw[$starting_index] != '\\'){
			$starting_index++;
		}

		$ending_index = $starting_index;
		while($raw[$ending_index] != '"'){
			$ending_index++;
		}
		return substr($raw, $starting_index, $ending_index-$starting_index);
	}


	function add_poem_to_db($con, $content, $keywords, $theme, $author, $image, $rhyme, $public){
		$content = mysqli_real_escape_string($con, $content);
		$sql_code = "INSERT INTO poems(content, keywords, theme, author, img, rhyme, published) VALUES ('$content', '$keywords', '$theme', '$author', '$image', '$rhyme', $public);";
		return mysqli_query($con, $sql_code) ? true : false;
	}

	function censor($str){
		$str = strtolower($str);
		$bad_words = array("fuck", "dick", "cock", "vagina", "nigger", "slut", "cunt", "prostitute", "sex", "anal");
		for($ind=0; $ind<10; $ind++){
			$sim = similar_text($str, $bad_words[$ind]);
			if($sim == 4){
				$str = "roses";
				break;
				
			}
		}
		
		return $str;
		
	}
?>