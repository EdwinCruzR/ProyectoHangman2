<?php 
   session_start();

   include("bd/conex.php");
   if(!isset($_SESSION['id'])){
    header("Location: index.php");
   }

   $id = $_SESSION['id'];
    $query = mysqli_query($conexion,"SELECT * FROM users WHERE id=$id");

    while($result = mysqli_fetch_assoc($query)){
        $name = $result['name'];
        $lastname = $result['lastname'];
        $email = $result['email'];
        $school = $result['school'];
        $avatar = $result['idavatar'];
        $rol = $result['roles_id'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Dashboard</title>
</head>
<body>
<nav>
    <div class="logo">
        <img src="./assets/img/hangman_logo_blue.png" alt="Logo">
    </div>
    <ul>
        <li><a href="#">Inicio</a></li>
        <li><a href="#">Productos</a>
        <!-- Submenu -->
        <ul class="submenu">
            <li><a href="#">Producto 1</a></li>
            <li><a href="#">Producto 2</a></li>
            <li><a href="#">Producto 3</a></li>
        </ul>
        </li>
        <li><a href="#">Servicios</a></li>
        <li><a href="#">Contacto</a></li>
    </ul>
    <!-- Botón de cierre de sesión -->
    <a href="./cerrarsesion.php"> <button id="logout-btn">Cerrar sesión</button> </a>
    
</nav>
    

<main>


</main>
</body>
</html>