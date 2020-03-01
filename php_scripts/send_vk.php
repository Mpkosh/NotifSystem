<?php 
	use \VK\Client\VKApiClient;

/*
$user_id -- reciever
it'll most definitely change but for now --
	user should agree to recieve messages from this group by: 
	- clicking "Allow messages" (https://vk.com/public192167393) OR
    - writing the first message to the group OR
    - clicking the widget on reg.php
*/

function vk_send($user_id,$user_surname,$user_name){
	$vk = new VKApiClient(); 
//from group's settings
 	$access_token = "222c285596176e4778f25d0c7d761326f1c27f8753e2e3cfd17f04ff2bad699e66b654006a288b76e2211";
//unique id to avoid sending the same message
 	$random_id = mt_rand(PHP_INT_MIN,PHP_INT_MAX);
 	$message = "$user_surname $user_name, cтатус Вашего заявления был изменен.";
 	
//sending the message from group (because group's access token is being used)
 	$response = $vk->messages()->send($access_token, array('user_id' => $user_id,
 				'random_id' => $random_id, 
 				'message' => $message)); 
}

?>
