<?php

	$to      = 'root@beerline1.ru';
	$subject = 'Обратная связь';
	$name    = $_REQUEST['name'];
	$tel     = $_REQUEST['tel'];
	$headers = 'From: root' . "\r\n";
	$message = 'Имя: '.$name.'<br>'.'Телефон: '.$tel;
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

	//mail($to, $subject, $message, $headers);


	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$name = strip_tags(trim($_POST["name"]));
		$name = str_replace(array("\r","\n"),array(" "," "),$name);
		$tel = strip_tags(trim($_POST["tel"]));

		if ( empty($name) OR empty($tel) ) {
			http_response_code(400);
			echo "Oops! There was a problem with your submission. Please complete the form and try again.";
			exit;
		}

		$recipient = "root@beerline1.ru";
		$subject = "New contact from $name";

		$email_content = "Name: $name\n";
		$email_content .= "Tel: $tel\n\n";
		$email_headers = "From: $name <st>";


		$captcha = "";

		if (isset($_POST["g-recaptcha-response"])) {
			$captcha = $_POST["g-recaptcha-response"];
		}

		if (!$captcha) {
			echo "Where is captcha?";
		}

		$secret = "PRIVATE_SECRET_KEY_HERE";
		$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha), true);

		if ($response["success"] != false) {

			if (mail($recipient, $subject, $email_content, $email_headers)) {
				http_response_code(200);
				echo "Thank You! Your message has been sent.";
			} else {
				http_response_code(500);
				echo "Oops! Something went wrong and we couldn't send your message.";
			}

		} else {
			echo "You shall not pass!";
		}

	} else {
		http_response_code(403);
		echo "There was a problem with your submission, please try again.";
	}
?>
