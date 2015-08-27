<?php

	header('Content-Type: application/json; charset=utf-8');

   require 'config.php';
   require 'PHPMailer/PHPMailerAutoload.php';

	$name = ( isset($_POST["name"]) ) ? trim( $_POST["name"] ) : '' ;
	$email = ( isset($_POST["email"]) ) ? trim( $_POST["email"] ) : '' ;
	$message = ( isset($_POST["message"]) ) ? trim( $_POST["message"] ) : '' ;
	$subject = ( isset($_POST["subject"]) ) ? trim( $_POST["subject"] ) : '' ;
	$errors = array();


	if(strlen($name) < 2) {
		if(!$name) {
			$errors[] = "You must enter a name.";
		} else {
			$errors[] = "Name must be at least 2 characters.";
		}
	}

	if(empty($email)) {
		$errors[] = "You must enter an email.";
	}

	if(!validEmail($email)) {
		$errors[] = "You must enter a valid email.";
	}


	if(strlen($message) < 10) {
		if(!$message) {
			$errors[] = "You must enter a message.";
		} else {
			$errors[] = "Message must be at least 10 characters.";
		}
	}

	if($errors) {
		die( json_encode(array("errors" => $errors)) );
	}

   if (empty($subject)) {
     $subject = $contact_form["subject_email"];
   }

	if(function_exists('stripslashes')) {
		$message = stripslashes($message);
	}


   $mail = new PHPMailer;
   $mail->CharSet='UTF-8';

   if (!empty($contact_form["smtp_host"])) {
      $mail->isSMTP();
      $mail->SMTPAuth = true; 
      $mail->Host = $contact_form["smtp_host"];
      $mail->Username =  $contact_form["smtp_username"];
      $mail->Password = $contact_form["smtp_password"];
      $mail->SMTPSecure =  $contact_form["smtp_secure"];
      $mail->Port = $contact_form["smtp_port"];
   }


   $mail->AddAddress( $contact_form["to_email"] , $contact_form["to_name"] );

   $mail->From = $email;
   $mail->FromName = $name;

   $mail->Subject = $subject;
   $mail->Body    = $message;

   if($mail->send()) {
      die(json_encode( array("success" => $contact_form["send_ok"]) ));
   } else {
      $errors[] = $contact_form["send_error"];
      die( json_encode(array("errors" => $errors)) );
   }



function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

?>