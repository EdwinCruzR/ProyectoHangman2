<?php 
   session_start();

   include("bd/conexion.php");
   if(isset($_SESSION['id'])){
    header("Location: dashpage.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
      <div class="container">
        <div class="box form-box">
            <?php 
             
              
              if(isset($_POST['submit'])){
                $email = mysqli_real_escape_string($conexion,$_POST['email']);
                $password = mysqli_real_escape_string($conexion,$_POST['password']);

                $result = mysqli_query($conexion,"SELECT * FROM users WHERE email='$email' AND password='$password' ");
                $row = mysqli_fetch_assoc($result);

                if(is_array($row) && !empty($row)){
                    $_SESSION['id'] = $row['id'];
                }else{
                    echo "<p>Email o contraseña incorrectos</p>
                        <br>";
                    echo "<a href='index.php'><button class='btn'>Atrás</button>";
         
                }
                //este es porsi ya habia iniciado antes q lo mande directo al dash
                if(isset($_SESSION['id'])){
                    header("Location: dashpage.php");
                }
              }else{

            
            ?>

            
            <h1>Login</h1>
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
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>