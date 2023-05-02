<style>
body{
    background-color: #fcee0a;
    display: flex;
    justify-content:center;
    margin-top:5%;
    margin-bottom:5%;
}
.main{
    padding: 40px;
    width: 250px;
    background-color: #00ffd2;
    border: 2px solid #fd0130;
}

h1{
    margin-left: 25%;
    margin-right: 25%;
}

a{
    color: black;
}

.pas{
    margin:2%;
    padding: 5%;
    border: 1px solid;
    border-color: #fd0130;
    border-radius: 3px;
}
.error {
    border-color: #fd0130;
  }
</style>
<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}
?>
<body>
    <div class="main">
    <h1>Форма</h1>
    
    <form action="index.php" method="POST">
            <div class="pas <?php if ($errors['name']) {print 'error';} ?>" >
                Имя:
                <input name="name" placeholder="Введите имя" 
                 value="<?php print $values['name']; ?>" />
            </div>

            <div class="pas <?php if ($errors['email']) {print 'error';} ?>">
                E-mail:
                <input name="email" type="email" placeholder="Введите почту" value="<?php print $values['email']; ?>"
	            >
            </div>

            <div class="pas" >
                Год рождения:
                <select id="yearB" name="year" >
                <?php
             for($i=1950;$i<=2023;$i++){
             if($values['year']==$i){
             printf("<option value=%d selected>%d </option>",$i,$i);
              }
             else{
             printf("<option value=%d>%d </option>",$i,$i);
            }
          }
          ?>
          </select>
            </div>

            <div class="pas <?php if ($errors['radio-1']) {print 'error';} ?>"> 
                Пол:<br>
                <input type="radio" name="radio-1" value="male"  <?php if($values['radio-1']=="male") {print 'checked';} ?>/>
                Мужской
                <input type="radio" name="radio-1" value="female" <?php if($values['radio-1']=="female") {print 'checked';} ?>/>
                Женский
            </div>



            <div class="pas <?php if ($errors['radio-2']) {print 'error';} ?>">
                Сколько конечностей?<br>
                    <input type="radio" name="radio-2" value="4" <?php if($values['radio-2']=="4") {print 'checked';} ?>/>
                    4

                    <input type="radio" name="radio-2" value="3" <?php if($values['radio-2']=="3") {print 'checked';} ?>/>
                    3

                    <input type="radio" name="radio-2" value="2" <?php if($values['radio-2']=="2") {print 'checked';} ?>/>
                    2

                    <input type="radio" name="radio-2" value="1" <?php if($values['radio-2']=="1") {print 'checked';} ?>/>
                    1
            </div>


            <div class="pas <?php if ($errors['super']) {print 'error';} ?>">
                Сверхспособности?
                
                    <select name="super[]" multiple="multiple">
                    <?php if ($errors['super']) {print 'class="error"';} ?> >
                    <option value="inv" <?php if($values['inv']==1){print 'selected';} ?>>Бессмертие</option>
                    <option value="walk" <?php if($values['walk']==1){print 'selected';} ?>>прохождение сквозь стены</option>
                    <option value="fly" <?php if($values['fly']==1){print 'selected';} ?>>левитация</option>
                    </select>
                
            </div>

            <div class="pas <?php if ($errors['bio']) {print 'error';} ?>">
                Биография?
                <textarea name="bio"><?php print $values['bio']; ?></textarea>
            </div>

                        <?php 
                $cl_e='';
                $ch='';
                if($values['check-1'] or !empty($_SESSION['login'])){
                $ch='checked';
                }
                if ($errors['check-1']) {
                $cl_e='class="error"';
                }
                if(empty($_SESSION['login'])){
                print('
                <div  '.$cl_e.' >
                <input name="check" type="checkbox" '.$ch.'> Я согласен дать данные <br>
                </div>');}
                ?>

                
                <input type="submit" value="Send" />
                </form>
            <?php
            if(empty($_SESSION['login'])){
            echo'
            <div class="login">
                <p> <a href="login.php">Если имеется аккаунт, то нажмите здесь</a></p>
            </div>';
            }
            else{
                echo '
                <div class="logout">
                <form action="index.php" method="post">
                    <input name="logout" type="submit" value="Выйти">
                </form>
                </div>';
            } ?>

            <!-- <div class="pas <?php if ($errors['check-1']) {print 'error';} ?> ">
                <input type="checkbox" name="check-1" <?php if($values['check-1']==TRUE){print 'checked';} ?>/> С контактом ознакомлен(а)
            </div>
            <p>
                Отправка формы:
                <input type="submit" value="Send" />
            </p> -->
        </form>
    </div>
</body>
