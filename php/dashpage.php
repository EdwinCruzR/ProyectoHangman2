<?php 
   session_start();

   include("../bd/conexion.php");
   if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
   }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./styledash.css">
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
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <div class="logo">
        <img src="../assets/img/hangman_logo_blue.png" alt="Logo">
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
        <p class="nav-link scrollto">ADMINISTRADOR DOCENTE</p>
        </li>
        <li class="nav-item">
        <p class="nav-link ">¡Hola!, <?php echo $name ?></p>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../arenagame.html">Jugar modo arena</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="">Unirse a sala</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Salas
          </a>
          <ul class="submenu dropdown-menu">
            <li><a href="#" onclick="toggleContent('sala_crear')">Crear</a></li>
            <li><a href="#" onclick="toggleContent('sala_consultar')">Colsultar</a></li>
            <li><a href="#" onclick="toggleContent('sala_editar')">Editar</a></li>
            <li><a href="#" onclick="toggleContent('sala_eliminar')">Eliminar</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Palabras
          </a>
          <ul class="submenu dropdown-menu">
            <li><a href="#" onclick="toggleContent('palabras_crear')">Crear</a></li>
            <li><a href="#" onclick="toggleContent('palabras_consultar')">Colsultar</a></li>
            <li><a href="#" onclick="toggleContent('palabras_editar')">Editar</a></li>
            <li><a href="#" onclick="toggleContent('palabras_eliminar')">Eliminar</a></li>
          </ul>
        </li>
        <li class="nav-item">
        <a class="nav-link scrollto" href="../tablageneral.html">Tabla general</a>
        </li>
        <li class="nav-item">
        <a href="./cerrarsesion.php"> <button id="logout-btn">Cerrar sesión</button> </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    

<main>
    <!-- <div class="floating-div">
        <h2>Contenido del div flotante</h2>
        <p>Este es un div flotante en el centro de la página.</p>
    </div> -->

    <div class="salas">
        <div id="sala_crear" class="content">
            <div class="form-container">
                <h1>Crear Sala de Juego</h1>

                <form id="gameForm" action="#" method="post">
                <label class="form-label" for="roomName">Nombre de la sala:</label>
                <input class="form-input" type="text" id="roomName" name="roomName" maxlength="50" required>

                <label class="form-label" for="roomDescription">Descripción de la sala:</label>
                <textarea class="form-input form-textarea" id="roomDescription" name="roomDescription" maxlength="300" required></textarea>

                <label class="form-label" for="unlimitedLives">¿Vidas ilimitadas?</label>
                <input class="checkbox-input" type="checkbox" id="unlimitedLives" name="unlimitedLives" onclick="toggleLivesInput()">

                <label class="form-label" for="numLives">Número de vidas:</label>
                <input class="form-input" type="number" id="numLives" name="numLives" min="1" max="10" value="3" disabled>

                <label class="form-label" for="showHints">¿Mostrar pistas?</label>
                <input class="checkbox-input" type="checkbox" id="showHints" name="showHints" checked>

                <label class="form-label" for="errorNumber">Mostrar pistas después del error número:</label>
                <input class="form-input" type="number" id="errorNumber" name="errorNumber" min="1" max="5" value="3">

                <label class="form-label" for="showFeedback">¿Mostrar retroalimentación?</label>
                <input class="checkbox-input" type="checkbox" id="showFeedback" name="showFeedback" checked>

                <label class="form-label" for="randomOrder">¿Orden de palabras aleatorio?</label>
                <input class="checkbox-input" type="checkbox" id="randomOrder" name="randomOrder">

                <label class="form-label" for="isOpen">¿Abierta?</label>
                <input class="checkbox-input" type="checkbox" id="isOpen" name="isOpen" checked>

                <label class="form-label" for="wordSource">Palabras de la sala:</label>
                <select class="select-input" id="wordSource" name="wordSource" onchange="toggleWordList()">
                    <option value="system">Palabras del sistema</option>
                    <option value="teacher">Palabras del docente</option>
                </select>

                <div class="word-list" id="wordList">
                    <label class="form-label">Seleccione la lista de palabras:</label>
                    <select class="select-input" id="wordListSelect">
                    <option value="list1">cocina</option>
                    <option value="list2">viajes</option>
                    <option value="list3">guerra</option>
                    </select>
                </div>

                <button class="form-button" type="submit">Crear Sala</button>
                </form>
            </div>
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
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

    function toggleLivesInput() {
      var numLivesInput = document.getElementById("numLives");
      numLivesInput.disabled = document.getElementById("unlimitedLives").checked;
    }

    function toggleWordList() {
      var wordList = document.getElementById("wordList");
      var wordSource = document.getElementById("wordSource");
      
      // Muestra la lista de palabras solo cuando se selecciona "Palabras del docente"
      wordList.style.display = (wordSource.value === "teacher") ? "block" : "none";
    }
  </script>
</body>
</html>