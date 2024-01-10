

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>Register</title>
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

include("../bd/conexion.php");
        if(isset($_POST['submit'])){
            $email = $_POST['email'];
            $name = $_POST['name'];
            $lastname = $_POST['lastname'];
            $school = $_POST['school'];
            $rol = $_POST['rol'];
            $password = $_POST['password'];

         //aqui checamos que el email no este ya en la bd

        $verifemail = mysqli_query($conexion,"SELECT email FROM users WHERE email='$email'");

        if(mysqli_num_rows($verifemail) !=0 ){
            echo "<div class='alert alert-danger' role='alert'>
                    Este email ya se uso, Prueba con otro porfi :)
                  </div>";
                    echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>
                    <a href='javascript:self.history.back()'><button type='button' class='btn btn-danger txt-center'>Atras</button></a>
                  </div>";
        }else{

            mysqli_query($conexion,"INSERT INTO users (email,password,name,lastname,school,roles_id) VALUES('$email','$password','$name','$lastname','$school','$rol')");
            
            echo "<div class='alert alert-success' role='alert'>
                    Registrado correctamente
                  </div>";
                    echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>
                    <a href='./login.php'><button type='button' class='btn btn-success'>Iniciar sesion</button></a>
                  </div>";
         

         }

         }else{
         
        ?>
            <div class="formbg-inner padding-horizontal--48">
              <span class="padding-bottom--15">Crear una cuenta</span>
              <form id="stripe-login" action="" method="post" onsubmit="return validateForm()">
                <div class="field padding-bottom--24">
                <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="ej. user1@hotmail.com" autocomplete="off" required>
                </div>
                <div class="field padding-bottom--24">
                    <label for="text">Nombre</label>
                    <input type="text" name="name" id="name" placeholder="ej. Juan" autocomplete="off" required>
                </div>
                <div class="field padding-bottom--24">
                    <label for="text">Apellidos</label>
                    <input type="text" name="lastname" id="lastname" placeholder="ej.Perez Perez" autocomplete="off" required>
                </div>
                <div class="field padding-bottom--24">
                    <label for="text">Escuela</label>
                    <input type="text" name="school" id="school" placeholder="ej. CBTis 150" autocomplete="off" required>
                </div>
                <div class="field padding-bottom--24">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password"  placeholder="*******" autocomplete="off" onkeyup="checkPasswordMatch()" required>
                </div>
                <div class="field padding-bottom--24">
                    <label for="password">Confirmar contraseña</label>
                    <input type="password" name="confirmPassword" id="confirmPassword"  placeholder="*******" autocomplete="off" onkeyup="checkPasswordMatch()" required>
                </div>
                <p id="passwordMatchMessage"></p>
                <div class="field padding-bottom--24">
                <label for="rol">Selecciona tu rol</label>
                    <select name="rol" id="rol" onchange="validateSelect()" required >
                        <option value="0">Selecciona una opción</option>
                        <option value="3">Alumno</option>
                        <option value="2">Docente</option>
                        <option value="4">Otro</option>
                    </select>
                </div>
                <p id="selectErrorMessage" style="color: red;"></p>
                <div class="field field-checkbox padding-bottom--24 flex-flex align-center">
                </div>
                <div class="field padding-bottom--24">
                <input type="submit" class="btn" name="submit" value="Registrate" required>
                </div>
              </form>
              <div class="links">
                    Ya tienes cuenta? <a href="./login.php">Inicia sesion</a>
                </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
        function checkPasswordMatch() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var message = document.getElementById("passwordMatchMessage");

            if (password === confirmPassword) {
              message.style.color = "green";
              message.innerHTML = "Las contraseñas coinciden";
              return true;
            } else {
              message.style.color = "red";
              message.innerHTML = "Las contraseñas no coinciden";
              return false;
            }
        }
        
        function validateSelect() {
            var selectElement = document.getElementById("rol");
            var errorMessage = document.getElementById("selectErrorMessage");

            if (selectElement.value === "0") {
              errorMessage.innerHTML = "Por favor, selecciona tu rol.";
              return false;
            } else {
              errorMessage.innerHTML = "";
              return true;
            }
        }
        
        function validateForm() {
            return checkPasswordMatch() && validateSelect();
        }
    </script>
</body>
</html>