<?php
    require '../vendor/autoload.php';
    require '../php_scripts/send_email.php';
    require '../php_scripts/send_vk.php';

function send($prop_id){
//database
    $host = '127.0.0.1';
    $db = 'app';
    $user = 'root';
    $pass = '';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $opt);

//getting user`s id
    $stmt = $pdo->prepare("SELECT id_parent FROM proposal 
                                    WHERE id = :prop_id");
    $stmt->execute([$prop_id]);
    $user_info = $stmt->fetch();
    $user_id = $user_info['id_parent'];

//getting user`s e-mail
    $stmt = $pdo->prepare("SELECT surname,name,status_email,email 
                                  FROM user WHERE id = :user_id");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
    $user_surname = $user_info['surname'];

    $user_name = $user_info['name'];
    $user_email = $user_info['email'];
    $status_email = $user_info['status_email'];

//getting user`s accounts
    $stmt = $pdo->prepare("SELECT * FROM user_accounts 
                                    WHERE user_id = :user_id");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();

//using only vk for now
    if (!empty($user_info['vk_id'])){
        vk_send($user_info['vk_id'],$user_surname,$user_name);
    }
    if ($status_email == 'verified'){
        email_send($user_email,$user_surname,$user_name);
    }
}
?>