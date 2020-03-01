<head >
    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?167">
    </script>
</head>
<body>
<div class="container">
        <form method='POST' action=''>
            <div class="col-3 VK-btn social-btn self-center">
                <button name="vk_btn">
                    <i class="fab fa-vk"></i> Вконтакте
                </button>
            </div>
        </form>
        
<!-- using widget for now. 
    doesn't return user_id yet -->
    <div id="vk_allow_messages_from_community"></div>
    <script type="text/javascript">
        VK.Widgets.AllowMessagesFromCommunity(
            "vk_allow_messages_from_community", {}, 192167393);
    </script>

<?php
    require '../vendor/autoload.php';
    require '../php_scripts/vk_info.php';
    require '../php_scripts/db_add.php';
    require '../php_scripts/verify.php';

//in case user fills everything manually (sure..)
    $last_name=$first_name=$nickname=$email=$phone_number=$sex=
    $job_place=$job_position=$registration_address=$relationship=
    $bdate=$study_place=$study_class='';

//after submitting -- add to database and verify e-mail
    if (isset($_POST['sbmt_btn'])){

        $date_registered = date("Y-m-d H:i:s");
        $db_user_id = add_to_user(
            $_POST['surname'],$_POST['name'],$_POST['midname'],
            $_POST['sex_select'],$_POST['email'],$_POST['phone_number'],
            $_POST['job_place'],$_POST['job_position'],$_POST['registration_address'],
            $_POST['relationship'],$_POST['birthday'],$_POST['study_place'],
            $_POST['study_class'],$date_registered
            );

        add_to_user_accounts("vk",$_POST['vk_id'],$db_user_id);
      //sending verif.code to e-mail
        $result_message = verify_email($db_user_id);
        print_r($result_message);

//if vk-code was sent
    }elseif (isset($_GET['code'])){
        list($vk_user_id,$last_name,$first_name,$nickname,$email,$bdate,$sex,
            $job_place,$job_position,$study_place,$study_class) = vk_fin_info();
        //at the end of registration vk-code will still be visible in the url -> ok?
    }

//button was pressed -> get user`s vk-code (to get access token)
    if (isset($_POST['vk_btn'])){
        vk_get_code();
    }

?>
    
    <div class="form-group row col-10 self-center">
    </div>
    <div class="form-group row col-10 mb-4" style="margin: 0 auto;">
        <div class="col-5">
            <hr>
        </div>
        <div class="col-2" style="text-align: center;display: flex;flex-direction: column;justify-content: center;margin: 0 auto;">
            Или
        </div>
        <div class="col-5">
            <hr>
        </div>
    </div>


    <form name='checking' method='POST',action=''>
    <input type='hidden' name='vk_id', value="<?php echo $vk_user_id; ?>">
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fas fa-user  fa-1x"></i></span>
        <input name='surname' type='text' class='form-control-plaintext col-10' value="<?php echo $last_name; ?>" placeholder='Фамилия'  id='surname_input'  required='true'>        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Фамилия">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fas fa-user  fa-1x"></i></span>
        <input  name='name'  type='text'  value="<?php echo $first_name; ?>"  class='form-control-plaintext col-10'  placeholder='Имя'  id='name_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Имя">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fas fa-user  fa-1x"></i></span>
        <input  name='midname'  type='text'  value="<?php echo $nickname; ?>"  class='form-control-plaintext col-10'  placeholder='Отчество'  id='midname_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Отчество">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fas fa-envelope fa-1x"></i></span>
        <input  name='email'  type='text'  value="<?php echo $email; ?>"  class='form-control-plaintext col-10'  placeholder='E-mail'  id='email_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="E-mail">-->
    </div>
    <!--
        <div class="form-group col-10 row input-text">
            <span class="col-1 icon-cont"><i class="fas fa-user-secret  fa-1x"></i></span>
            <input  name='password'  type='password'  value=''  class='form-control-plaintext col-10'  placeholder='Пароль'  id='password_input'  data-triggered='manual'  data-toggle='tooltip'  data-html='true'  title='Пароль должен содержать больше 6 символов'  required='true' >
        </div>
        <div class="form-group col-10 row input-text">
            <span class="col-1 icon-cont"><i class="fas fa-user-secret  fa-1x"></i></span>
            <input type="password" class="form-control-plaintext col-10" placeholder="Подтвердите пароль" id="password_repeat_input"  data-trigger="manual" data-toggle="tooltip" data-html="true" title="Пароли не совпадают" data-placement="left">
        </div> 
    -->
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont" style="left: 0.9rem;"><i class="fa fa-phone fa-flip-horizontal" aria-hidden="true"></i></span>
        <input  name='phone_number'  type='text'  value="<?php echo $phone_number; ?>"  class='form-control-plaintext col-10'  placeholder='Мобильный телефон'  id='phone_number_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Мобильный телефон">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont" style="left: 0.9rem;"><i class="fa fa-venus-mars fa-flip-horizontal" aria-hidden="true"></i></span>
        <select name="sex_select" class="form-control col-10 input-select sex-selector" id="sex_input">
            <option default id='select-placeholder'>Пол</option>
            <option value='0'<?php if ($sex == '2'){echo 'selected="selected"';}?>>М</option>
            <option value='1'<?php if ($sex == '1'){echo 'selected="selected"';}?>>Ж</option>
        </select>
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
        <input  name='job_place'  type='text'  value="<?php echo $job_place; ?>"  class='form-control-plaintext col-10'  placeholder='Место работы'  id='job_place_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Должность">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
        <input  name='job_position'  type='text'  value="<?php echo $job_position; ?>"  class='form-control-plaintext col-10'  placeholder='Должность'  id='job_position_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Должность">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont" style="left: 0.9rem;"><i class="fa fa-map-marker fa-flip-horizontal" aria-hidden="true"></i></span>
        <input  name='registration_address'  type='text'  value="<?php echo $registration_address; ?>"  class='form-control-plaintext col-10'  placeholder='Домашний адрес'  id='registration_address_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Домашний адрес">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="far fa-heart"></i></span>
        <input  name='relationship'  type='text'  value="<?php echo $relationship; ?>"  class='form-control-plaintext col-10'  placeholder='Степень родства'  id='relationship_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Домашний адрес">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fas fa-calendar-day"></i></span>
        <input  name='birthday'  type='date'  value="<?php echo $bdate; ?>"  class='form-control-plaintext col-10'  placeholder='Дата рождения'  id='birthday_input'  max='2020-02-27'  required='true' >    
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
        <input  name='study_place'  type='text'  value="<?php echo $study_place; ?>"  class='form-control-plaintext col-10'  placeholder='Место учебы'  id='study_place_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Должность">-->
    </div>
    <div class="form-group col-10 row input-text">
        <span class="col-1 icon-cont"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
        <input  name='study_class'  type='text'  value="<?php echo $study_class; ?>"  class='form-control-plaintext col-10'  placeholder='Класс'  id='study_class_input'  required='true' >        <!--            <input type="text" class="form-control-plaintext col-10" placeholder="Должность">-->
    </div>
    <div class="form-group col-10 row self-center">
        <input id="privacy" type="checkbox" class="col-1">
        <label for="privacy" class="col-11">Я согласен(-а) на обработку персональных данных
            в соответствии с п.4 ст.9 Федерального закона
            от 27.07.2006 №152-ФЗ "О персональных данных"</label>
    </div>
    <div class="form-group col-10 row self-center">
        <input name="sbmt_btn" type="submit" class="btn btn-dark col-12 input-btn" id="create_account_btn" value="СОЗДАТЬ АККАУНТ">
    </div>
    </form>
</div>
</body>
