<?php 
  session_start();
  include("../bd/conexion.php");
  if (isset($_SESSION['id'])) {
    header("Location: ./dashpage.php");
  }
      
  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>Login</title>
</head>
<body>
<div class="login-root">
    <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
      <div class="loginbackground box-background--white padding-top--64">
        <div class="loginbackground-gridContainer">
          <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
            <div class="box-root" style="background-image: linear-gradient(white 0%, rgb(247, 250, 252) 33%); flex-grow: 1;">
            </div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
            <div class="box-root box-divider--light-all-2 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
            <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
            <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
            <div class="box-root box-background--gray100 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
            <div class="box-root box-background--cyan200 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
            <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
            <div class="box-root box-background--gray100 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
            <div class="box-root box-divider--light-all-2 animationRightLeft tans3s" style="flex-grow: 1;"></div>
          </div>
        </div>
      </div>
      <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
        <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
          <h1><a href="http://blog.stackfindover.com/" rel="dofollow"></a></h1>
        </div>
        <div class="formbg-outer">
          <div class="formbg"> 
            <?php 
            
            if(isset($_POST['submit'])){
                $email = mysqli_real_escape_string($conexion,$_POST['email']);
                $password = mysqli_real_escape_string($conexion,$_POST['password']);

                $result = mysqli_query($conexion,"SELECT * FROM users WHERE email='$email' AND password='$password' ");
                $row = mysqli_fetch_assoc($result);

                if(is_array($row) && !empty($row)){
                    $_SESSION['id'] = $row['id'];
                }else{
                    echo "<div class='alert alert-danger' role='alert'>
                    Error al iniciar sesion, contraseña o email incorrectos!
                  </div>";
                    echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>
                    <a href='./login.php'><button type='button' class='btn btn-danger txt-center'>Atras</button></a>
                  </div>";
        
                }
                //este es porsi ya habia iniciado antes q lo mande directo al dash
                if(isset($_SESSION['id'])){
                    header("Location: ./dashpage.php");
                }
            }else{

            
            ?>
            <div class="formbg-inner padding-horizontal--48">
              <span class="padding-bottom--15">Iniciar Sesion</span>
              <form id="stripe-login" action="" method="post">
                <div class="field padding-bottom--24">
                  <label for="email">Email</label>
                  <input type="email" name="email">
                </div>
                <div class="field padding-bottom--24">
                  <div class="grid--50-50">
                    <label for="password">Password</label>
                    <div class="reset-pass">
                      <a href="#">Forgot your password?</a>
                    </div>
                  </div>
                  <input type="password" name="password">
                </div>
                <div class="field padding-bottom--24">
                <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
              </form>
              <div class="links">
                    No tienes cuenta? <a href="./registro.php">Registrate ahora</a>
                </div>
            </div>
            <?php } ?>
          </div> 
        </div>
      </div>
    </div>
  </div>
      <!-- <div class="container">
        <div class="box form-box"> -->
           

            
            <!-- <h1>Login</h1>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                    No tienes cuenta? <a href="./registro.php">Registrate ahora</a>
                </div>
            </form> -->
        
</body>
</html>