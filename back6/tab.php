<style>
body{
    background-color: #fcee0a;
    display: flex;
    justify-content:center;
    margin-top:5%;
    margin-bottom:5%;
}
  .form1{
    max-width: 960px;
    text-align: center;
    margin: 0 auto;
  }
  .error {
    border: 3px solid #fd0130;
  }
  .hidden{
    display: none;
  }
</style>
<body>
  <div class="table1">
    <table border="1">
      <tr>
        <th>Name</th>
        <th>EMail</th>
        <th>Year</th>
        <th>Pol</th>
        <th>Limbs</th>
        <th>Superpower</th>
        <th>Bio</th>
      </tr>
      <?php
      foreach($users as $user){
          echo '
            <tr>
              <td>'.$user['name'].'</td>
              <td>'.$user['email'].'</td>
              <td>'.$user['year'].'</td>
              <td>'.$user['pol'].'</td>
              <td>'.$user['limbs'].'</td>
              <td>';
                $user_pwrs=array(
                    "inv"=>FALSE,
                    "walk"=>FALSE,
                    "fly"=>FALSE,
                );
                foreach($pwrs as $pwr){
                    if($pwr['per_id']==$user['id']){
                        if($pwr['name']=='inv'){
                            $user_pwrs['inv']=TRUE;
                        }
                        if($pwr['name']=='walk'){
                            $user_pwrs['walk']=TRUE;
                        }
                        if($pwr['name']=='fly'){
                            $user_pwrs['fly']=TRUE;
                        }                      
                    }
                }
                if($user_pwrs['inv']){echo 'inv<br>';}
                if($user_pwrs['walk']){echo 'walk<br>';}
                if($user_pwrs['fly']){echo 'fly<br>';}
              echo '</td>
              <td>'.$user['bio'].'</td>
              <td>
                <form method="get" action="index.php">
                  <input name=edit_id value='.$user['id'].' hidden>
                  <input type="submit" value=Edit>
                </form>
              </td>
            </tr>';
       }
      ?>
    </table>
    <?php
    printf('Пользователи с inv: %d <br>',$pwrs_count[0]);
    printf('Пользователи с walk: %d <br>',$pwrs_count[1]);
    printf('Пользователи с fly: %d <br>',$pwrs_count[2]);
    ?>
  </div>
</body>
