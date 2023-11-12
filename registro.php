<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">

        <?php 
        include("bd/conexion.php");
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
            echo "<p>Este email ya se uso, Prueba con otro porfi :)</p>
                <br>";
            echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
        }else{

            mysqli_query($conexion,"INSERT INTO users (email,password,name,lastname,school,roles_id) VALUES('$email','$password','$name','$lastname','$school','$rol')");
            

            echo "<p>Registrado correctamente</p>
                  <br>";
            echo "<a href='index.php'><button class='btn'>Inicia sesion</button>";
         

         }

         }else{
         
        ?>

            <h1>Registrate</h1>
            <form action="" method="post">

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="ej. user1@hotmail.com" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="text">Nombre</label>
                    <input type="text" name="name" id="name" placeholder="ej. Juan" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="text">Apellidos</label>
                    <input type="text" name="lastname" id="lastname" placeholder="ej.Perez Perez" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="text">Escuela</label>
                    <input type="text" name="school" id="school" placeholder="ej. CBTis 150" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password"  placeholder="*******" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="rol">Selecciona tu rol</label>
                    <select name="rol" id="rol" required >
                        <option value="3">Alumno</option>
                        <option value="2">Docente</option>
                        <option value="4">Otro</option>
                    </select>
                </div>
                

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Registrate" required>
                </div>
                <div class="links">
                    Ya tienes cuenta? <a href="login.php">Inicia sesion</a>
                </div>
            </form>
        </div>
        <?php } ?>
      </div>
</body>
</html>