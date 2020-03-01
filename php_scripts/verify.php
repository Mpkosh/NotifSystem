<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require '../vendor/autoload.php';


//if it's opened from the letter
if (isset($_GET['hash'])){
    $pdo = db();
  //check if hash is the same
    $user_id = $_GET["id"];
    $hash = $_GET["hash"];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE id = :user_id 
                                                AND verification_key_email = :hash");
    $stmt->execute([$user_id, $hash]);

  //if smth is returned => there's a match! => verified
    if ($stmt->fetch()){
        $stmt = $pdo->prepare("UPDATE user SET status_email = 'verified', 
                                verification_key_email = '' WHERE id = :user_id");
        $stmt->execute([$user_id]);
            echo "Your e-mail is verified!";
    }else{
        echo "Not verified: hash is not the same. </br>
              Well, at least you`re good at making sad faces.. </br>
              See? That`s what matters in life, right?!";
        }
}


//send verif.code
function verify_email($user_id){

    $pdo = db();
  //link which is sent to e-mail
    $verif_link = "http://localhost/to_check/graphql/php_scripts/verify.php";
  //getting e-mail and its status
    $stmt = $pdo->prepare("SELECT surname,name,status_email,email 
                                FROM user WHERE id = :user_id");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
    $user_surname = $user_info['surname'];
    $user_name = $user_info['name'];
    $user_email = $user_info['email'];
    $status_email = $user_info['status_email'];

  //giving verification key
    $hash = bin2hex(random_bytes(16));
    $stmt = $pdo->prepare("UPDATE user SET verification_key_email = :hash 
                                                WHERE id = :user_id");
    $stmt->execute([$hash,$user_id]);

  //sending
    $mail = new PHPMailer(true);
    try {
      //server settings
      //enable verbose debug output
        #$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        
      //send using SMTP
        $mail->isSMTP();
      //set the SMTP server to send through 
        $mail->Host = 'ssl://smtp.yandex.ru';
      //enable SMTP authentication  
        $mail->SMTPAuth = true;

        $mail->Username = 'testing.sending';
        $mail->Password = 'itwillwork100%';
      //enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 465;
      //sent messages are not shown anywhere..
        $mail->setFrom('testing.sending@yandex.ru', 'testing.sending');
        $mail->addAddress($user_email, 'Someone');     // 

        //$mail->addAttachment('C:/Users/justj/Desktop/2.jpg');

        $mail->CharSet = "utf-8";
      //content
      //set email format to HTML
        $mail->isHTML(true);
                
        $mail->Subject = 'Подтвердите свою почту';
        $mail->Body    = "
            <b>$user_surname $user_name,</b><br/>
            Подтвердите почту, пройдя по ссылке: 
            <a href='$verif_link?id=$user_id&hash=$hash'>Verify my e-mail</a>";
      //text for non-HTML mail clients
        $mail->AltBody = "$user_surname $user_name, подтвердите почту, 
                            пройдя по ссылке: $verif_link?id=$user_id&hash=$hash";

        $mail->send();
        $result_message = 'Verification link has been sent to your e-mail!';
    }
    catch (Exception $e) {
        $result_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    return $result_message;
}

function db(){
  //database
    $host = '127.0.0.1';
    $db   = 'app';
    $user = 'root';
    $pass = '';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    return (new PDO($dsn, $user, $pass, $opt));
}
?>