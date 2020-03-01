<?php
	require '../vendor/autoload.php';


function add_to_user($surname,$name,$midname,$sex,$email,$phone_number,
                     $job_place,$job_position,$registration_address,$relationship,
                     $birthday,$study_place,$study_class,$date_registered){
  //function db() -- from verify.php
    $pdo = db();
  //adding to 'user' + getting the new user`s id
    $stmt = $pdo->prepare("INSERT into user (
    			surname,name,midname,sex,phone_number,email,
				registration_address,job_place,job_position,relationship,
				study_place,study_class,date_registered,birthday
    	) VALUES (
    			:surname,:name,:midname,:sex,:phone_number,:email,
    			:registration_address,:job_place,:job_position,:relationship,
    			:study_place,:study_class,:date_registered,:birthday
				)");
    $stmt->execute([
    		$surname,$name,$midname,$sex,$phone_number,$email,
    		$registration_address,$job_place,$job_position,$relationship,
    		$study_place,$study_class,$date_registered,$birthday
    		]);
    $stmt->fetch();
    $db_user_id = $pdo->lastInsertId();

    return $db_user_id;
}


function add_to_user_accounts($social_net,$sn_id,$db_user_id){
    $pdo = db();
    $stmt = $pdo->prepare("INSERT into user_accounts (user_id, {$social_net}_id) 
                                          VALUES (:db_user_id, :sn_id)");
    $stmt->execute([$db_user_id,$sn_id]);
    $stmt->fetch();
}

?>