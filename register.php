<?php 
require_once("includes/config.php");
require_once("includes/classes/Account.php");
$account = new Account($con);
require_once("includes/handlers/register_handler.php");
require_once("includes/handlers/login_handler.php");

if(isset($_SESSSION['logedIn'])){
  header('Location: index.php');
  exit();
}

function remember($name){
  if(isset($_POST[$name])){
    echo $_POST[$name];
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In or Register</title>
 
    <link href="assets/css/register.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
  </head>
  <body>
    <?php     
      if(isset($_POST['submitRegister'])){
        echo "<script>
                  $(document).ready(function(){
                    $('.registerFrame.login').hide();
                    $('.registerFrame.register').show();
                  });
              </script>";
      }else{
        echo "<script>
                  $(document).ready(function(){
                    $('.registerFrame.login').show();
                    $('.registerFrame.register').hide();
                  });
              </script>";
      }
     ?>
   

<!------- CONTAINER ------->
    <div id="container">
<?php   
    if(isset($mssgs) AND isset($color)){
      echo "<div class='message'>";
        foreach ($mssgs as $msg) {
          echo "<span style='color:".$color.";'>".$msg."</span>";
        }
      echo "</div>";
    }
?>
 

<!------- LOGIN FORM ------->
      <div class="registerFrame login">
        <form action="register.php" class="registerForm login" method="POST"> 
          <h1>Log In</h1>
          <input type="email" id="inputEmail" name="loginEmail" placeholder="Email address" value="<?php remember('loginEmail') ?>" required autofocus>
          <input type="password" id="inputPassword" name="loginPassword" placeholder="Password" required>
          <button name="submitLogin" type="submit">Log In</button>
          <h2 class="hideLogin">Don't have an account?</h2>
          <h3>&copy; Karol Nowak</h3>
        </form>
      </div> 

<!------- REGISTER FORM ------->
      <div class="registerFrame register" >
        <form action="register.php" class="registerForm register" method="POST"> 
          <h1>Register</h1>
          <input type="text" id="firstName" name="firstName" placeholder="Fisrt Name" value="<?php remember('firstName') ?>" required>
          <input type="text" id="lastName" name="lastName" placeholder="Last Name" value="<?php remember('lastName') ?>" required>
          <input type="email" name="email" placeholder="Email address" value="<?php remember('email') ?>" required>
          <input name="password1" type="password" placeholder="Password" required>
          <input name="password2" type="password" placeholder="Repeat Password" required>
          <button name="submitRegister" type="submit">Sign In</button>
          <h2 class="hideRegister">Allready have an account?</h2>
          <h3>&copy; Karol Nowak</h3>
        </form>
      </div>   
  </div>
   
</body>
</html>
