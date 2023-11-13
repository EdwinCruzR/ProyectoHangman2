<?php 
   session_start();

   include("bd/conex.php");
   if(!isset($_SESSION['id'])){
    header("Location: index.php");
   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/styledash.css">
    <title>Dashboard</title>
</head>
<body>
<?php
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
<nav>
    <div class="logo">
        <img src="./assets/img/hangman_logo_blue.png" alt="Logo">
    </div>
    <ul>
        <li>ADMINISTRADOR DOCENTE</li>
        <li>¡Hola!, <?php echo $name ?></li>
        <li><a href="dashpage.php">Inicio</a></li>
        <li><a href="arenagame.html">Jugar modo arena</a></li>
        <li><a href="">Unirse a sala</a></li>
        <li>Salas
        <!-- Submenu -->
        <ul class="submenu">
            <li><a href="#" onclick="toggleContent('sala_crear')">Crear</a></li>
            <li><a href="#" onclick="toggleContent('sala_consultar')">Colsultar</a></li>
            <li><a href="#" onclick="toggleContent('sala_editar')">Editar</a></li>
            <li><a href="#" onclick="toggleContent('sala_eliminar')">Eliminar</a></li>
        </ul>

        <li>Palabras
        <!-- Submenu -->
        <ul class="submenu">
            <li><a href="#" onclick="toggleContent('palabras_crear')">Crear</a></li>
            <li><a href="#" onclick="toggleContent('palabras_consultar')">Colsultar</a></li>
            <li><a href="#" onclick="toggleContent('palabras_editar')">Editar</a></li>
            <li><a href="#" onclick="toggleContent('palabras_eliminar')">Eliminar</a></li>
        </ul>

        <li><a class="nav-link scrollto" href="tablageneral.html">Tabla general</a></li>
        </li>
        
    </ul>
    <!-- Botón de cierre de sesión -->
    <a href="./cerrarsesion.php"> <button id="logout-btn">Cerrar sesión</button> </a>
    
</nav>
    

<main>
    <!-- <div class="floating-div">
        <h2>Contenido del div flotante</h2>
        <p>Este es un div flotante en el centro de la página.</p>
    </div> -->

    <div class="salas">
        <div id="sala_crear" class="content">
        <h2>Contenido para crear salas</h2>
        <p>Este es el contenido que se mostrará cuando se seleccione la Opción 1.</p>
        </div>
    
        <div id="sala_consultar" class="content">
            <h2>Contenido ara consultar salas</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 2.</p>
        </div>
    
        <div id="sala_editar" class="content">
            <h2>Contenido ara editar salas</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div>
    
        <div id="sala_eliminar" class="content">
            <h2>Contenido ara eliminar salas</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div>
    </div>
    <div class="palabras">
        <div id="palabras_crear" class="content">
        <h2>Contenido para crear palabras</h2>
        <p>Este es el contenido que se mostrará cuando se seleccione la Opción 1.</p>
        </div>
    
        <div id="palabras_consultar" class="content">
            <h2>Contenido para consultar palabras</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 2.</p>
        </div>
    
        <div id="palabras_editar" class="content">
            <h2>Contenido para editar palabras</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div>
    
        <div id="palabras_eliminar" class="content">
            <h2>Contenido para eliminar palabras</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div>
    </div>

</main>

<script>
    function toggleContent(contentId) {
      // Oculta los demás contenidos
      var contents = document.querySelectorAll('.content');
      contents.forEach(function (content) {
        if (content.id !== contentId) {
          content.classList.remove('visible');
        }
      });

      // Muestra u oculta el contenido según su estado actual
      var selectedContent = document.getElementById(contentId);
      if (selectedContent) {
        selectedContent.classList.toggle('visible');
      }
    }
  </script>
</body>
</html>