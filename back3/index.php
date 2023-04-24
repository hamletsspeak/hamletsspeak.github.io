<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] != 'POST'){
	print_r('Не POST методы не принимаются');
}
$errors = FALSE;
if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['year']) || empty($_POST['bio']) || empty($_POST['check1']) || $_POST['check1'] == false || !isset($_POST['super']) ){
	print_r('Заполните пустые поля!');
	exit();
}
$name = $_POST['name'];
$email = $_POST['email'];
$birth_year = $_POST['year'];
$pol = $_POST['radio-1'];
$limbs = intval($_POST['radio-2']);
$superpowers = array($_POST['super']);
$bio= $_POST['bio'];

$bioreg = "/^\s*\w+[\w\s\.,-]*$/";
$reg = "/^\w+[\w\s-]*$/";
$mailreg = "/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/";
$list_sup = array('inv','walk','fly');

if(!preg_match($reg,$name)){
	print_r('Неверный формат имени');
	exit();
}
if($limbs == 0){
	print_r('Нет конечностей');
	exit();
}
if(!preg_match($bioreg,$bio)){
	print_r('Неверный формат биографии');
	exit();
}
if(!preg_match($mailreg,$email)){
	print_r('Неверный формат email');
	exit();
}
if($pol !== 'male' && $pol !== 'female'){
	print_r('Неверный формат пола');
	exit();
}
foreach($superpowers as $checking){
	if(array_search($checking,$list_sup)=== false){
			print_r('Неверный формат суперсил');
			exit();
	}
}

$user = 'u52976';
$pass = '1701674';
$db = new PDO('mysql:host=localhost;dbname=u52976', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
try {
  $stmt = $db->prepare("INSERT INTO FORM SET name=:name, email=:email, year=:byear, pol=:pol, limbs=:limbs, bio=:bio");
  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':byear', $birth_year);
  $stmt->bindParam(':pol', $pol);
  $stmt->bindParam(':limbs', $limbs);
  $stmt->bindParam(':bio', $bio);

  if($stmt->execute()==false){
  print_r($stmt->errorCode());
  print_r($stmt->errorInfo());
  exit();
  }
  
  
  $id = $db->lastInsertId();
  $sppe= $db->prepare("INSERT INTO SUPER SET name=:name, per_id=:person");
  $sppe->bindParam(':person', $id);
  foreach($superpowers as $inserting){
	$sppe->bindParam(':name', $inserting);
	if($sppe->execute()==false){
	  print_r($sppe->errorCode());
	  print_r($sppe->errorInfo());
	  exit();
	}
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

print_r("Данные отправлены в бд");
?>
