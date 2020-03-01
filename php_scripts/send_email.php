<?php 
 	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;


function email_send($user_email,$user_surname,$user_name){
		$mail = new PHPMailer(true);
		
		try {
		//server settings
		//enable verbose debug output
		    #$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		    
		//send using SMTP
		    $mail->isSMTP();
		//set the SMTP server to send through 
		    //$mail->Host = ' smtp.mailtrap.io';
		    $mail->Host = 'ssl://smtp.yandex.ru';
		//enable SMTP authentication  
		    $mail->SMTPAuth = true;
		    
		    //$mail->Username = '99c647bd0be874';
		    //$mail->Password = '97dbd3dd093839';
			$mail->Username = 'testing.sending';
		    $mail->Password = 'itwillwork100%';
		//enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
		    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		    //$mail->Port = 25;
		    $mail->Port = 465;
		//sent messages are not shown anywhere..
		    $mail->setFrom('testing.sending@yandex.ru', 'testing.sending');
		    $mail->addAddress($user_email, 'Someone');     // 

		    //$mail->addAttachment('C:/Users/justj/Desktop/2.jpg');

		    $mail->CharSet = "utf-8";
		//content
		//set email format to HTML
		    $mail->isHTML(true);
		    $mail->Subject = 'Новый статус заявления';
		    $mail->Body = "
		    	<b>$user_surname $user_name,</b> <br/>
		    	Статус Вашего заявления был изменен.
		    	<br/>
		    ";
		//text for non-HTML mail clients
		    $mail->AltBody = "$user_surname $user_name, cтатус Вашего заявления был изменен.";

		    $mail->send();
		    //echo 'Message has been sent';
			}
		catch (Exception $e) {
	    	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	    }
	}
?>