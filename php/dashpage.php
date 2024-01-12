<?php 
   session_start();

   include("../bd/conexion.php");
   if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/styledash.css">
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/salas.css">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <title>Dashboard</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <div class="logo">
        <img src="../assets/img/stickman_admin.png" alt="Logo">
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse nav" id="navbarNavDropdown">
      <ul class="navbar-nav">
      <div class="left">
        <li class="nav-item">
        <p class="nav-link scrollto">ADMINISTRADOR DOCENTE</p>
        </li>
        <li class="nav-item">
        <p class="nav-link ">¡Hola!, <?php echo $name ?></p>
        </li>   
      </div> 
      <div class="right">   
        <li class="nav-item">
            <a class="nav-link" href="../arenagame.html">Jugar modo arena</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="./unirseSala.php">Unirse a sala</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Salas
          </a>
          <ul class="submenu dropdown-menu">
            <li><a href="#sala_crear" onclick="toggleContent('sala_crear')">Crear</a></li>
            <li><a href="#sala_consultar" onclick="toggleContent('sala_consultar')">Colsultar</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Palabras
          </a>
          <ul class="submenu dropdown-menu">
            <li><a href="#palabras_crear" onclick="toggleContent('palabras_crear')">Crear</a></li>
            <li><a href="#palabras_consultar" onclick="toggleContent('palabras_consultar')">Colsultar</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Listas
          </a>
          <ul class="submenu dropdown-menu">
            <li><a href="#listas_crear" onclick="toggleContent('listas_crear')">Crear</a></li>
            <li><a href="#listas_consultar" onclick="toggleContent('listas_consultar')">Colsultar</a></li>
          </ul>
        </li>
        <li class="nav-item">
        <a class="nav-link scrollto" href="../tablageneral.html">Tabla general</a>
        </li>
        <li class="nav-item">
        <a href="./cerrarsesion.php"> <button id="logout-btn">Cerrar sesión</button> </a>
        </li>  
      </div>  
      </ul>
    </div>
  </div>
</nav>
    

<main>
    <div class="salas">
        <div id="sala_crear" class="content">
              <div class="form-container">
              <?php 
              if(isset($_POST['submit_crear_sala'])){

                function makeRoomCode() {
                  $roomcode = '';
                  $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
          
                  for ($i = 0; $i < 6; $i++) {
                      $roomcode .= $caracteres[rand(0, strlen($caracteres) - 1)];
                  }
                  return $roomcode;
                }
            
                $roomName = mysqli_real_escape_string($conexion, $_POST['roomName']);
                $roomDescription = mysqli_real_escape_string($conexion, $_POST['roomDescription']);
                $lives = (isset($_POST["unlimitedLives"]) && $_POST["unlimitedLives"] == "on") ? -1 : intval($_POST['numLives']);
                $clue = (isset($_POST["showHints"]) && $_POST["showHints"] == "on") ? 1 : 0;
                $clueafter = ($clue == 1) ? intval($_POST['errorNumber']) : -1;
                $feedback = (isset($_POST["showFeedback"]) && $_POST["showFeedback"] == "on") ? 1 : 0;
                $isopen = (isset($_POST["isOpen"]) && $_POST["isOpen"] == "isOpen") ? 1 : 0;
                $selectedStatus = isset($_POST["statusSource"]) ? $_POST["statusSource"] : "";
                
                switch ($selectedStatus) {
                    case 'hasstartdatetime':
                        $hasstartdatetime = 1;
                        $hasenddatetime = 0;
                        $timestampClose = NULL;
                        $timestampOpen = mysqli_real_escape_string($conexion, $_POST["timestampOpen"]);
                        break;
            
                    case 'hasenddatetime':
                        $hasstartdatetime = 0;
                        $hasenddatetime = 1;
                        $timestampClose = mysqli_real_escape_string($conexion, $_POST["timestampClose"]);
                        $timestampOpen = NULL;
                        break;
            
                    case 'setTime':
                        $hasstartdatetime = 1;
                        $hasenddatetime = 1;
                        $timestampOpen = mysqli_real_escape_string($conexion, $_POST["timestampOpen"]);
                        $timestampClose = mysqli_real_escape_string($conexion, $_POST["timestampClose"]);
                        break;
            
                    case 'WithoutH':
                        $hasstartdatetime = 0;
                        $hasenddatetime = 0;
                        $timestampOpen = NULL;
                        $timestampClose = NULL;
                        break;
                }
            
                $isgeneral = (isset($_POST["wordSource"]) && $_POST["wordSource"] == "system") ? 1 : 0;
                $random = (isset($_POST["randomOrder"]) && $_POST["randomOrder"] == "on") ? 1 : 0;
                
                $roomcode = '';
                do {
                    $roomcode = makeRoomCode();
                    $verifCode = mysqli_query($conexion, "SELECT roomcode FROM room WHERE roomcode='$roomcode'");
                } while (mysqli_num_rows($verifCode) != 0);
            
                $qrcode = 'https://www.cbtis150.edu.mx/hangman/php/roomgame.php?r=' . $roomcode;
            
                // Usa sentencias preparadas para prevenir inyección de SQL
                $insertCreate = mysqli_prepare($conexion, "INSERT INTO room (roomname, description, lives, clue, clueafter, feedback, random, isopen, hasstartdatetime, startdatetime, hasenddatetime, enddatetime, isgeneral, roomcode, qrstring, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($insertCreate, "ssiiiiiissssssis", $roomName, $roomDescription, $lives, $clue, $clueafter, $feedback, $random, $isopen, $hasstartdatetime, $timestampOpen, $hasenddatetime, $timestampClose, $isgeneral, $roomcode, $qrcode, $id);
                mysqli_stmt_execute($insertCreate);
            
                if(mysqli_stmt_affected_rows($insertCreate) === 0){
                    echo 'Error en la creación de la sala: ' . mysqli_error($conexion);
                } else {
                    echo 'Sala creada correctamente';
                    header("Location: dashpage.php");
                    exit;
                }

              }else{
              
              ?>
                  <h1>Crear Sala de Juego</h1>
                  <form id="gameForm" action="./dashpage.php" method="post">
                  <label class="form-label" for="roomName">Nombre de la sala:</label>
                  <input class="form-input" type="text" id="roomName" name="roomName" maxlength="50" required>

                  <label class="form-label" for="roomDescription">Descripción de la sala:</label>
                  <textarea class="form-input form-textarea" id="roomDescription" name="roomDescription" maxlength="300" required></textarea>

                  <label class="form-label" for="unlimitedLives">¿Vidas ilimitadas?</label>
                  <input class="checkbox-input" type="checkbox" id="unlimitedLives" name="unlimitedLives" onclick="toggleLivesInput()" checked>

                  <label class="form-label" for="numLives">Número de vidas:</label>
                  <input class="form-input" type="number" id="numLives" name="numLives" min="1" max="10" value="3" disabled>

                  <label class="form-label" for="showHints">¿Mostrar pistas?</label>
                  <input class="checkbox-input" type="checkbox" id="showHints" name="showHints" onclick="toggleCluesInput()"checked>

                  <label class="form-label" for="errorNumber">Mostrar pistas después del error número:</label>
                  <input class="form-input" type="number" id="errorNumber" name="errorNumber" min="1" max="5" value="3" >

                  <label class="form-label" for="showFeedback">¿Mostrar retroalimentación?</label>
                  <input class="checkbox-input" type="checkbox" id="showFeedback" name="showFeedback" checked>

                  <label class="form-label" for="randomOrder">¿Orden de palabras aleatorio?</label>
                  <input class="checkbox-input" type="checkbox" id="randomOrder" name="randomOrder">

                  <!-- <label class="form-label" for="isOpen">¿Abierta?</label>
                  <input class="checkbox-input" type="checkbox" id="isOpen" name="isOpen" checked> -->

                  <label class="form-label" for="isOpen">Estado de entrada:</label>
                  <select class="select-input" id="isOpen" name="isOpen">
                      <option value="isOpen">Abierta</option>
                      <option value="isClose">Cerrada</option>
                  </select>

                  <label class="form-label" for="statusSource">Establecer horario:</label>
                  <select class="select-input" id="statusSource" name="statusSource" onchange="toggleRoomStatus()">
                      <option value="WithoutH">Sin horario</option>
                      <option value="hasstartdatetime">Solo entrada</option>
                      <option value="hasenddatetime">Solo cierre</option>
                      <option value="setTime">Entrada y cierre</option>
                  </select>

                  <div class="settimeopen" id="settimeopen">
                    <label class="form-label" for="timestampOpen">Hora de apertura:</label>
                    <input class="form-input" type="datetime-local" id="timestampOpen" name="timestampOpen" >
                  </div>
                  <div class="settimeclose" id="settimeclose">
                    <label class="form-label" for="timestampClose">Hora de cierre:</label>
                    <input class="form-input" type="datetime-local" id="timestampClose" name="timestampClose" >
                  </div>
                    
                  <label class="form-label" for="wordSource">Palabras de la sala:</label>
                  <select class="select-input" id="wordSource" name="wordSource" onchange="toggleWordList()">
                      <option value="system">Palabras del sistema</option>
                      <option value="teacher">Palabras del docente</option>
                  </select>

                  <div class="word-list" id="wordList">
                      <label class="form-label" for="wordListSelect">Seleccione la lista de palabras:</label>
                      <select class="select-input" id="wordListSelect">
                      <option value="list1">cocina</option>
                      <option value="list2">viajes</option>
                      <option value="list3">guerra</option>
                      </select>
                  </div>
                  <input type="submit" class="form-button" name="submit_crear_sala" value="Crear Sala" required>
                  </form>
              </div>
              <?php } ?>
        </div>
    
        <div id="sala_consultar" class="content">
            <h2>Tus salas</h2>
            <div class="contenido">

            <?php 
            $consulta_salas = mysqli_query($conexion,"SELECT * FROM Room WHERE user_id=$id");
                    
            if ($consulta_salas->num_rows > 0) {
            ?>

            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Vidas</th>
                    <th>Pista</th>
                    <th>Pista Después de</th>
                    <th>¿Mostrar Feedback?</th>
                    <th>¿Palabras Random?</th>
                    <th>¿Está Abierta?</th>
                    <th>Fuente de palabras</th>
                    <th>Creada</th>
                    <th>Código de Sala</th>
                    <th>QR</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($consulta_salas)): ?>
                    <tr>
                        <th><?= $row['id'] ?></th>
                        <th><?= $row['roomname'] ?></th>
                        <th><?= $row['description'] ?></th>
                        <th><?= (($row['lives'] == -1)? "ilimitadas" : $row['lives'])     ?></th>
                        <th><?=  (($row['clue'] == 1)? "Si" : "No") ?></th>
                        <th><?php echo $row['clueafter'] ?> intentos</th>
                        <th><?= (($row['feedback'] == 1)? "Si" : "No")  ?></th>
                        <th><?= (($row['random'] == 1)? "Si" : "No") ?></th>
                        <th><?= (($row['isopen'] == 1)? "Si" : "No") ?></th>
                        <th><?= (($row['isgeneral'] == 1)? "Sistema" : "Lista") ?></th>
                        <th><?= $row['timestamp'] ?></th>
                        <th><?= $row['roomcode'] ?></th>
                        <th>
                        <div id="qrcode"></div>

                        <script>
                            // Contenido para el código QR
                            var contenidoQR = '<?php echo $row['qrstring']; ?>';

                            // Configuración del código QR
                            var opcionesQR = {
                                text: contenidoQR,
                                width: 128,
                                height: 128
                            };

                            // Genera el código QR en el contenedor con el id "qrcode"
                            var qrcode = new QRCode(document.getElementById("qrcode"), opcionesQR);
                        </script>
                        </th>
                        <th><a href="editarSala.php?id=<?= $row['id'] ?>" class="users-table--edit">Editar</a><br>
                        <a href="eliminarSala.php?id=<?= $row['id'] ?>" onClick="return confirm('¿Estás seguro de eliminar a <?php echo $row['id']; ?>')" class="users-table--delete" >Eliminar</a></th>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php 
            }else {
                echo 'No hay salas registradas.';
            }
            
        ?>
            </div>
        </div>
    
        <!-- <div id="sala_editar" class="content">
            <h2>Contenido para editar salas</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div> -->
    
        <!-- <div id="sala_eliminar" class="content">
            <h2>Contenido para eliminar salas</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div> -->
    </div>
    <div class="palabras">
    <div id="palabras_crear" class="content">
    <div class="form-container">
              <?php 
              if(isset($_POST['submit_crear_palabra'])){

                  $word = $_POST['wordName'];
                  $type = $_POST['typeListSelect'];
                  $clue = $_POST['clue'];
                  $wordPast = $_POST['wordPast'];
                  $eg = $_POST['eg'];
                  
                  $insertCreate = mysqli_query($conexion,"INSERT INTO words (word, type, clue, simplepast, example, user_id) VALUES ('$word', '$type','$clue', '$wordPast', '$eg', '$id')");
                  
                  if(mysqli_num_rows($insertCreate) !=0 ){
                    echo "<div class='alert alert-danger' role='alert'>
                    Error al crear la palabra
                  </div>";
                    echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>
                    <a href='javascript:self.history.back()'><button type='button' class='btn btn-danger txt-center'>Atras</button></a>
                  </div>";
                  }                 

              }else{
              
              ?>
                  <h1>Crear Palabras</h1>

                  <form id="gameForm" action="./dashpage.php" method="post">
                  <label class="form-label" for="wordName">Nombre de la palabra:</label>
                  <input class="form-input" type="text" id="wordName" name="wordName" maxlength="50" required>

                  <label class="form-label" for="typeListSelect">Seleccione el tipo de verbo:</label>
                  <select class="select-input" id="typeListSelect" name="typeListSelect">
                  <option value="R">Regular</option>
                  <option value="I">Irregular</option>
                  </select>

                  <label class="form-label" for="clue">Pista de la palabra:</label>
                  <textarea class="form-input form-textarea" id="clue" name="clue" maxlength="300" required></textarea>

                  <label class="form-label" for="wordPast">Pasado simple de la palabra:</label>
                  <input class="form-input" type="text" id="wordPast" name="wordPast" maxlength="50" required>

                  <label class="form-label" for="eg">Ejemplo de la palabra:</label>
                  <textarea class="form-input form-textarea" id="eg" name="eg" maxlength="300" required></textarea>

                  <input type="submit" class="form-button" name="submit_crear_palabra" value="Crear palabra" required>
                  </form>
              </div>
              <?php } ?>
        </div>
    
        <div id="palabras_consultar" class="content">
            <h2>Tus palabras</h2>
            <div class="contenido">

            <?php 
            $consulta_salas = mysqli_query($conexion,"SELECT * FROM words WHERE user_id=$id AND isactive=1");
                    
            if ($consulta_salas->num_rows > 0) {
            ?>

            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Palabra</th>
                    <th>Tipo</th>
                    <th>Pista</th>
                    <th>Pasado simple</th>
                    <th>Ejemplo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($consulta_salas)): ?>
                    <tr>
                        <th><?= $row['id'] ?></th>
                        <th><?= $row['word'] ?></th>
                        <th><?= (($row['type'] == "I")? "Irregular" : "Regular") ?></th>
                        <th><?= $row['clue'] ?></th>
                        <th><?= $row['simplepast'] ?></th>
                        <th><?= $row['example'] ?></th>
                        <th><a href="editarPalabras.php?ic=<?= $row['id'] ?>" class="users-table--edit">Editar</a><br>
                        <a href="eliminarPalabras.php?id=<?= $row['id'] ?>" onClick="return confirm('¿Estás seguro de eliminar a <?php echo $row['id']; ?>')" class="users-table--delete" >Eliminar</a></th>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php 
            }else {
                echo 'No hay palabras registradas.';
            }
            
        ?>
            </div>
        </div>
        <!-- <div id="palabras_editar" class="content">
            <h2>Contenido para editar palabras</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div>
    
        <div id="palabras_eliminar" class="content">
            <h2>Contenido para eliminar palabras</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div> -->
    </div>

    <div class="listas">
    <div id="listas_crear" class="content">
    <div class="form-container">
              <?php 
              if(isset($_POST['submit_crear_categoria'])){

                  $word = $_POST['wordName'];
                  $type = $_POST[''];
                  $clue = $_POST['clue'];
                  $wordPast = $_POST['wordPast'];
                  $eg = $_POST['eg'];
                  
                  $insertCreate = mysqli_query($conexion,"INSERT INTO words (word, type, clue, simplepast, example, user_id) VALUES ('$word', '$type','$clue', '$wordPast', '$eg', '$id')");
                  
                  if(mysqli_num_rows($insertCreate) !=0 ){
                    echo "<div class='alert alert-danger' role='alert'>
                    Error al crear la palabra
                  </div>";
                    echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>
                    <a href='javascript:self.history.back()'><button type='button' class='btn btn-danger txt-center'>Atras</button></a>
                  </div>";
                  }                 

              }else{
              
              ?>
                  <h1>Crear Lista</h1>

                  <form id="gameForm" action="./dashpage.php" method="post">
                  <label class="form-label" for="wordName">Nombre de la palabra:</label>
                  <input class="form-input" type="text" id="wordName" name="wordName" maxlength="50" required>

                  <label class="form-label" for="typeListSelect">Seleccione el tipo de verbo:</label>
                  <select class="select-input" id="typeListSelect">
                  <option value="list1">Regular</option>
                  <option value="list2">Irregular</option>
                  </select>

                  <label class="form-label" for="clue">Pista de la palabra:</label>
                  <textarea class="form-input form-textarea" id="clue" name="clue" maxlength="300" required></textarea>

                  <label class="form-label" for="wordPast">Pasado simple de la palabra:</label>
                  <input class="form-input" type="text" id="wordPast" name="wordPast" maxlength="50" required>

                  <label class="form-label" for="eg">Ejemplo de la palabra:</label>
                  <textarea class="form-input form-textarea" id="eg" name="eg" maxlength="300" required></textarea>

                  <input type="submit" class="form-button" name="submit_crear_palabra" value="Crear palabra" required>
                  </form>
              </div>
              <?php } ?>
        </div>
    
        <div id="listas_consultar" class="content">
            <h2>Tus listas</h2>
            <div class="contenido">

            <?php 
            $consulta_salas = mysqli_query($conexion,"SELECT * FROM words");
                    
            if ($consulta_salas->num_rows > 0) {
            ?>

            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Palabra</th>
                    <th>Tipo</th>
                    <th>Pista</th>
                    <th>Pasado simple</th>
                    <th>Ejemplo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($consulta_salas)): ?>
                    <tr>
                        <th><?= $row['id'] ?></th>
                        <th><?= $row['word'] ?></th>
                        <th><?= (($row['type'] == "I")? "Irregular" : "Regular") ?></th>
                        <th><?= $row['clue'] ?></th>
                        <th><?= $row['simplepast'] ?></th>
                        <th><?= $row['example'] ?></th>
                        <th><a href="editarPalabras.php?id=<?= $row['id'] ?>" class="users-table--edit">Editar</a><br>
                        <a href="eliminarPalabras.php?id=<?= $row['id'] ?>" onClick="return confirm('¿Estás seguro de eliminar a <?php echo $row['id']; ?>')" class="users-table--delete" >Eliminar</a></th>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php 
            }else {
                echo 'No hay palabras registradas.';
            }
            
        ?>
            </div>
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

    function toggleLivesInput() {
      var numLivesInput = document.getElementById("numLives");
      numLivesInput.disabled = document.getElementById("unlimitedLives").checked;
    }

    function toggleCluesInput(){
      var cluesInputs = document.getElementById("errorNumber");
      if(document.getElementById("showHints").checked) {
        cluesInputs.disabled=false;
      } else {
        cluesInputs.disabled=true;
      }
    }

    function toggleRoomStatus() {
      var statusSource = document.getElementById("statusSource");
      var divClose = document.getElementById("settimeclose");
      var divOpen = document.getElementById("settimeopen");
      
      if (divClose && divOpen) {
        switch (statusSource.value) {
          case "setTime":
            divClose.style.display = "block";
            divOpen.style.display = "block";
            break;
          case "hasstartdatetime":
            divOpen.style.display = "block";
            divClose.style.display = "none";
            break;
          case "hasenddatetime":
            divClose.style.display = "block";
            divOpen.style.display = "none";
            break;
        }
      }
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