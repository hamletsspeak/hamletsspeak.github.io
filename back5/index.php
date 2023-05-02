<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('<a href="login.php">Войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> чтобы измененить данные.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
    setcookie('name_value', '', 100000);
    setcookie('email_value', '', 100000);
    setcookie('year_value', '', 100000);
    setcookie('pol_value', '', 100000);
    setcookie('limb_value', '', 100000);
    setcookie('bio_value', '', 100000);
    setcookie('inv_value', '', 100000);
    setcookie('walk_value', '', 100000);
    setcookie('fly_value', '', 100000);
    setcookie('check_value', '', 100000);
  }

  $errors = array();
  $error=FALSE;
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['radio-1'] = !empty($_COOKIE['pol_error']);
  $errors['radio-2'] = !empty($_COOKIE['limb_error']);
  $errors['super'] = !empty($_COOKIE['super_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['check-1'] = !empty($_COOKIE['check_error']);
  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя или у него неверный формат (only English)</div>';
    $error=TRUE;
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните имейл или у него неверный формат</div>';
    $error=TRUE;
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Выберите год.</div>';
    $error=TRUE;
  }
  if ($errors['radio-1']) {
    setcookie('pol_error', '', 100000);
    $messages[] = '<div class="error">Выберите пол.</div>';
    $error=TRUE;
  }
  if ($errors['radio-2']) {
    setcookie('limb_error', '', 100000);
    $messages[] = '<div class="error">Укажите кол-во конечностей.</div>';
    $error=TRUE;
  }
  if ($errors['super']) {
    setcookie('super_error', '', 100000);
    $messages[] = '<div class="error">Выберите суперспособности(хотя бы одну).</div>';
    $error=TRUE;
  }
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию или у неё неверный формат (only English)</div>';
    $error=TRUE;
  }
  if ($errors['check-1']) {
    setcookie('check_error', '', 100000);
    $messages[] = '<div class="error">Вы должны согласиться с условиями использования данных.</div>';
    $error=TRUE;
  }
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['year'] = empty($_COOKIE['year_value']) ? 0 : $_COOKIE['year_value'];
  $values['radio-1'] = empty($_COOKIE['pol_value']) ? '' : $_COOKIE['pol_value'];
  $values['radio-2'] = empty($_COOKIE['limb_value']) ? '' : $_COOKIE['limb_value'];
  $values['inv'] = empty($_COOKIE['inv_value']) ? 0 : $_COOKIE['inv_value'];
  $values['walk'] = empty($_COOKIE['walk_value']) ? 0 : $_COOKIE['walk_value'];
  $values['fly'] = empty($_COOKIE['fly_value']) ? 0 : $_COOKIE['fly_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
  $values['check-1'] = empty($_COOKIE['check_value']) ? FALSE : $_COOKIE['check_value'];

  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (!$error && !empty($_COOKIE[session_name()]) && !empty($_SESSION['login'])) {
    $user = 'u52976';
    $pass = '1701674';
    $db = new PDO('mysql:host=localhost;dbname=u52976', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    try{
      $get=$db->prepare("SELECT * FROM form WHERE id=?");
      $get->bindParam(1,$_SESSION['uid']);
      $get->execute();
      $inf=$get->fetchALL();
      $values['name']=$inf[0]['name'];
      $values['email']=$inf[0]['email'];
      $values['year']=$inf[0]['year'];
      $values['radio-1']=$inf[0]['pol'];
      $values['radio-2']=$inf[0]['limbs'];
      $values['bio']=$inf[0]['bio'];

      $get2=$db->prepare("SELECT name FROM super WHERE per_id=?");
      $get2->bindParam(1,$_SESSION['uid']);
      $get2->execute();
      $inf2=$get2->fetchALL();
      for($i=0;$i<count($inf2);$i++){
        if($inf2[$i]['name']=='fly'){
          $values['fly']=1;
        }
        if($inf2[$i]['name']=='inv'){
          $values['inv']=1;
        }
        if($inf2[$i]['name']=='walk'){
          $values['walk']=1;
        }
      }
    }
    catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
    }
    printf('Произведен вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {
  if(!empty($_POST['logout'])){
    session_destroy();
    header('Location: index.php');
  }
  else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $year = $_POST['year'];
    $pol=$_POST['radio-1'];
    $limbs=$_POST['radio-2'];
    $powers=$_POST['super'];
    $bio=$_POST['bio'];
    if(empty($_SESSION['login'])){
      $check=$_POST['check'];
    }
    //Регулярные выражения
    $bioregex = "/^\s*\w+[\w\s\.,-]*$/";
    $nameregex = "/^\w+[\w\s-]*$/";
    $mailregex = "/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/";
    $errors = FALSE;

    if (empty($name) || (!preg_match($nameregex,$name))) {
      setcookie('name_error', '1', time() + 24*60 * 60);
      setcookie('name_value', '', 100000);
      $errors = TRUE;
    }
    else {
      setcookie('name_value', $fio, time() + 60 * 60);
      setcookie('name_error','',100000);
    }

    if (empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL) ||
     (!preg_match($mailregex,$email))) {
      setcookie('email_error', '1', time() + 24*60 * 60);
      setcookie('email_value', '', 100000);
      $errors = TRUE;
    }
    else {
      setcookie('email_value', $email, time() + 60 * 60);
      setcookie('email_error','',100000);
    }

    if ($year=='year') {
      setcookie('year_error', '1', time() + 24 * 60 * 60);
      setcookie('year_value', '', 100000);
      $errors = TRUE;
    }
    else {
      setcookie('year_value', intval($year), time() + 60 * 60);
      setcookie('year_error','',100000);
    }

    if (!isset($pol)) {
      setcookie('pol_error', '1', time() + 24 * 60 * 60);
      setcookie('pol_value', '', 100000);
      $errors = TRUE;
    }
    else {
      setcookie('pol_value', $pol, time() + 60 * 60);
      setcookie('pol_error','',100000);
    }

    if (!isset($limbs)) {
      setcookie('limb_error', '1', time() + 24 * 60 * 60);
      setcookie('limb_value', '', 100000);
      $errors = TRUE;
    }
    else {
      setcookie('limb_value', $limbs, time() + 60 * 60);
      setcookie('limb_error','',100000);
    }

    if (!isset($powers)) {
      setcookie('super_error', '1', time() + 24 * 60 * 60);
      setcookie('inv_value', '', 100000);
      setcookie('walk_value', '', 100000);
      setcookie('fly_value', '', 100000);
      $errors = TRUE;
    }
    else {
      $apw=array(
        "inv_value"=>0,
        "walk_value"=>0,
        "fly_value"=>0,
      );
    foreach($powers as $pwer){
      if($pwer=='inv'){setcookie('inv_value', 1, time() + 12 * 30 * 24 * 60 * 60); $apw['inv_value']=1;} 
      if($pwer=='walk'){setcookie('walk_value', 1, time() + 12*30 * 24 * 60 * 60);$apw['walk_value']=1;} 
      if($pwer=='fly'){setcookie('fly_value', 1, time() + 12*30 * 24 * 60 * 60);$apw['fly_value']=1;} 
      }
    foreach($apw as $c=>$val){
      if($val==0){
        setcookie($c,'',100000);
        }
      }
    }
    
    if ((empty($bio)) || (!preg_match($bioregex,$bio))) {
      setcookie('bio_error', '1', time() + 24 * 60 * 60);
      setcookie('bio_value', '', 100000);
      $errors = TRUE;
    }
    else {
      setcookie('bio_value', $bio, time() + 12 * 30 * 24 * 60 * 60);
      setcookie('bio_error', '', 100000);
    }
    
    if(empty($_SESSION['login'])){
      if(!isset($check)){
        setcookie('check_error','1',time()+ 24*60*60);
        setcookie('check_value', '', 100000);
        $errors=TRUE;
      }
      else{
        setcookie('check_value',TRUE,time()+ 60*60);
        setcookie('check_error','',100000);
      }
    }
    if ($errors) {
      setcookie('save','',100000);
      header('Location: login.php');
    }
    else {
      setcookie('name_error', '', 100000);
      setcookie('email_error', '', 100000);
      setcookie('year_error', '', 100000);
      setcookie('pol_error', '', 100000);
      setcookie('limb_error', '', 100000);
      setcookie('super_error', '', 100000);
      setcookie('bio_error', '', 100000);
      setcookie('check_error', '', 100000);
    }
    
	$user = 'u52976';
	$pass = '1701674';	
    $db = new PDO('mysql:host=localhost;dbname=u52976', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']) and !$errors) {
      $id=$_SESSION['uid'];
      $upd=$db->prepare("UPDATE form SET name=:name, email=:email, year=:byear, pol=:pol, limbs=:limbs, bio=:bio WHERE id=:id");
      $cols=array(
        ':name'=>$name,
        ':email'=>$email,
        ':byear'=>$year,
        ':pol'=>$pol,
        ':limbs'=>$limbs,
        ':bio'=>$bio
      );
      foreach($cols as $k=>&$v){
        $upd->bindParam($k,$v);
      }
      $upd->bindParam(':id',$id);
      $upd->execute();
      $del=$db->prepare("DELETE FROM super WHERE per_id=?");
      $del->execute(array($id));
      $upd1=$db->prepare("INSERT INTO super SET name=:power,per_id=:id");
      $upd1->bindParam(':id',$id);
      foreach($powers as $pwr){
        $upd1->bindParam(':power',$pwr);
        $upd1->execute();
      }
    }
    else {
      if(!$errors){
        $login = 'u'.substr(uniqid(),-5);
        $pass = substr(md5(uniqid()),0,10);
        $pass_hash=password_hash($pass,PASSWORD_DEFAULT);
        setcookie('login', $login);
        setcookie('pass', $pass);

        try {
          $stmt = $db->prepare("INSERT INTO form SET name=:name, email=:email, year=:byear, pol=:pol, limbs=:limbs, bio=:bio");
          $stmt->bindParam(':name',$_POST['name']);
          $stmt->bindParam(':email',$_POST['email']);
          $stmt->bindParam(':byear',$_POST['year']);
          $stmt->bindParam(':pol',$_POST['radio-1']);
          $stmt->bindParam(':limbs',$_POST['radio-2']);
          $stmt->bindParam(':bio',$_POST['bio']);
          $stmt -> execute();

          $id=$db->lastInsertId();

          $usr=$db->prepare("INSERT INTO userlogin SET id=?,login=?,password=?");
          $usr->bindParam(1,$id);
          $usr->bindParam(2,$login);
          $usr->bindParam(3,$pass_hash);
          $usr->execute();

          $pwr=$db->prepare("INSERT INTO super SET name=:power, per_id=:id");
          $pwr->bindParam(':id',$id);
          foreach($_POST['super'] as $powers){
            $pwr->bindParam(':power',$powers); 
            $pwr->execute();  
          }
        }
        catch(PDOException $e){
          print('Error : ' . $e->getMessage());
          exit();
        }
      }
    }
    if(!$errors){
      setcookie('save', '1');
    }
    header('Location: ./');
  }
}
