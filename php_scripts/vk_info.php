<?php
    use \VK\Client\VKApiClient;
    use \VK\OAuth\VKOAuth;
    use \VK\OAuth\VKOAuthDisplay;
    use \VK\OAuth\Scopes\VKOAuthUserScope;
    use \VK\OAuth\VKOAuthResponseType;


// getting user's code (Authorization Code Flow)
function vk_get_code(){
    $oauth = new VKOAuth();
  //app id 
    $client_id=7324944;
    $display = VKOAuthDisplay::PAGE; 
  //for Standalone VK-apps:
    //$redirect_uri="https://oauth.vk.com/blank.html";
  //for WebSite VK-apps (should be the same domain as in app`s settings!):
    $redirect_uri="http://localhost/to_check/graphql/php_scripts/reg.php";
  //what access(?) we want to get
    $scope = array(VKOAuthUserScope::EMAIL); 
    $state = 'secret_state_code';

    $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE,$client_id, 
                                               $redirect_uri,$display,$scope,$state); 
    header("location: $browser_url");

}

// all info in a suitable format 
// should it be merged with vk_user_data()?..
function vk_fin_info(){
    $oauth = new VKOAuth(); 
    $client_id=7324944;
  //for Standalone VK-apps:
    //$redirect_uri="https://oauth.vk.com/blank.html";
  //for WebSite VK-apps (the same in app`s settings):
    $redirect_uri="http://localhost/to_check/graphql/php_scripts/reg.php";
  //secret key from app`s settings
    $client_secret="A04Z5KyNj80OxIEbjAkV"; 

    list($user_id,$last_name,$first_name,$nickname,$email,$bdate,$sex,
         $job_place,$position,$study_place,$study_class) = vk_user_data(
                                                        $oauth,$client_id,
                                                        $redirect_uri,$client_secret);
    $bdate_fin = bdate2fin($bdate);

    return array($user_id,$last_name,$first_name,$nickname,$email,$bdate_fin,
                 $sex,$job_place,$position,$study_place,$study_class);
}

//even if user's bdate is not shown, it can still be accessed (??)
function bdate2fin($bdate){
      //$bdate -- "DD(without '0').MM(without '0').YYYY"
        $bdate_nums = explode(".",$bdate);
      //adding "0" in front of month\day if necessary
        if (strlen($bdate_nums[1])==1){
            $bdate_nums[1]="0$bdate_nums[1]";
        }
        if (strlen($bdate_nums[0])==1){
            $bdate_nums[0]="0$bdate_nums[0]";
        }
      //changing bdate into "YYYY-MM-DD" for <input type='date'>
        $bdate_fin = "$bdate_nums[2]-$bdate_nums[1]-$bdate_nums[0]";

        return $bdate_fin;
    }


// getting detailed info
function vk_user_data($oauth,$client_id, $redirect_uri,$client_secret){ 
      //getting user's access token + their id and e-mail    
        $code = $_GET["code"];
        $response = $oauth->getAccessToken($client_id,$client_secret,$redirect_uri,$code);

        $access_token = $response['access_token']; 
        $user_id = $response['user_id'];
        if (!empty($response['email'])){
            $email = $response['email'];
        }else{
            $email = '';
        }

    /*
      getting user's info
        'contacts'[mobile_phone] -- only for Standalone Vk-apps!
        'nickname' =(in vk api docs)= middle name
        'occupation' doesn't show school!
    */
        $vk = new VKApiClient(); 
        $response = $vk->users()->get($access_token, array(
            'user_ids' => $user_id,
            'fields' => array('bdate','career','nickname','sex','schools'),
        )); 

        $response = $response[0];
        $first_name = $response['first_name'];
        $last_name = $response['last_name'];
        $bdate = $response['bdate'];
        $nickname = $response['nickname'];
        $sex = $response['sex'];

        list($job_place,$position,$study_place,$study_class) = get_occupation($response);


        return array($user_id,$last_name,$first_name,$nickname,$email,$bdate,
                     $sex,$job_place,$position,$study_place,$study_class);
    }

//job or school info
function get_occupation($response){
    $job_place=$position=$study_place=$study_class='';

    //may be group_id instead of company -- we don`t include this type of job (should we??)
    if (!empty($response['career'])){
      //getting last job info
        end($response['career']);
        $last_job_ind = key($response['career']);
        $last_job = $response['career'][$last_job_ind]; 
      //if user is still working here
        if (!array_key_exists('until', $last_job)){
            $job_place = $last_job['company'] ?? '';
            $position = $last_job['position'] ?? '';
        }

    }elseif (!empty($response['schools'])){
      //getting last school info
        end($response['schools']);
        $last_school_ind = key($response['schools']);
        $last_school = $response['schools'][$last_school_ind];
      //excluding music/art/sport schools
        if (array_key_exists('type',$last_school)&&
           (($last_school['type']<5)||($last_school['type']>7))){
          //if user is still studying here
          //'year_to' and 'year_graduated'???  
            if ((!array_key_exists('year_to', $last_school))||
                        ($last_school['year_to']>date('Y'))){
                $study_place = $last_school['name'] ?? '';
                $study_class = $last_school['class'] ?? '';
              //there's a whitespace in front of numbers; letters are ok
                $study_class = str_replace(' ','',$study_class);
            }
        }
    }
    return array($job_place,$position,$study_place,$study_class);


}
?>    