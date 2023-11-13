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
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="./salas.css">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
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
    <div class="salas">
        <div id="sala_crear" class="content">
          <div class="contenido">
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


                  $roomName = $_POST['roomName'];
                  $roomDescription = $_POST['roomDescription'];
                  $lives = (isset($_POST["unlimitedLives"]) && $_POST["unlimitedLives"] == "on")? -1 : intval($_POST['numLives']);
                  $clue = (isset($_POST["showHints"]) && $_POST["showHints"] == "on")? 1 : 0;
                  $clueafter = (isset($_POST["showHints"]) && $_POST["showHints"] == "on")? intval($_POST['errorNumber']) : -1;
                  $feedback = (isset($_POST["showFeedback"]) && $_POST["showFeedback"] == "on")? 1 : 0 ;
                  $isopen = (isset($_POST["isOpen"]) && $_POST["isOpen"] == "on")? 1 : 0 ;
                  $random = (isset($_POST["randomOrder"]) && $_POST["randomOrder"] == "on")? 1 : 0 ;
                  $roomcode = '';
                  do {
                      $roomcode = makeRoomCode();
                      $verifCode = mysqli_query($conexion, "SELECT roomcode FROM room WHERE roomcode='$roomcode'");
                  } while (mysqli_num_rows($verifCode) != 0);

                  $qrcode = "https://www.cbtis150.edu.mx/hangman/";
                  
                  $insertCreate = mysqli_query($conexion,"INSERT INTO room (roomname, description, lives, clue, clueafter, feedback, random, isopen, roomcode, qrstring, user_id) VALUES ('$roomName', '$roomDescription', '$lives', '$clue', '$clueafter', '$feedback', '$random', '$isopen', '$roomcode', '$qrcode', '$id')");
                  
                  if(mysqli_num_rows($insertCreate) !=0 ){
                      echo "<div class='message'>
                              <p>Error al crear la sala</p>
                          </div> <br>";
                      echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                  }else{
          
                      mysqli_query($conexion,"INSERT INTO users (email,password,name,lastname,school,roles_id) VALUES('$email','$password','$name','$lastname','$school','$rol')");
                      
          
                      echo "<p>Sala creada correctamente</p>
                            <br>";
                      echo "<a href='dashpage.php'><button class='btn'>Inico</button>";
                  
          
                  }

              }else{
              
              ?>
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
                  <input type="submit" class="form-button" name="submit_crear_sala" value="Crear Sala" required>
                  </form>
              </div>
              <?php } ?>
          </div>
        </div>
    
        <div id="sala_consultar" class="content">
            <h2>Tus salas</h2>
            <div class="contenido">

            <?php 
            
            $consulta_salas = mysqli_query($conexion,"SELECT * FROM room WHERE user_id=$id");
                    
            if ($consulta_salas->num_rows > 0) {
                
                // Itera sobre los resultados y muestra cada fila en la tabla
                // fetch_assoc() hace que los datos de la consulta se pongan en un array
                while ($fila = $consulta_salas->fetch_assoc()) {
                    echo '<table>';
                    echo '<tr>';
                    echo '<th>Dato</th>';
                    echo '<td>Valor</td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<td>' . $fila['id'] . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Nombre de la sala</th>';
                    echo '<td>' . $fila['roomname'] . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Descripción</th>';
                    echo '<td>' . $fila['description'] . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Vidas</th>';
                    echo '<td>' . $fila['lives'] . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Pista</th>';
                    echo '<td>' . (($fila['clue'] == 1)? "Si" : "No") .'</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Pista Después de</th>';
                    echo '<td>' . $fila['clueafter'] . ' intentos</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Mostrar Feedback?</th>';
                    echo '<td>' . (($fila['feedback'] == 1)? "Si" : "No")  . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>palabras Random?</th>';
                    echo '<td>' . (($fila['random'] == 1)? "Si" : "No"). '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>¿Está Abierta?</th>';
                    echo '<td>' . (($fila['isopen'] == 1)? "Si" : "No") . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Creada</th>';
                    echo '<td>' . $fila['timestamp'] . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>Código de Sala</th>';
                    echo '<td>' . $fila['roomcode'] . '</td>';
                    echo '</tr>';
            
                    echo '<tr>';
                    echo '<th>QR</th>';
                    
                    ?>

                    <td>
                                    <!-- Contenedor para el código QR -->
                        <div id="qrcode"></div>

                        <script>
                            // Contenido para el código QR
                            var contenidoQR = '<?php echo $fila['qrstring']; ?>';

                            // Configuración del código QR
                            var opcionesQR = {
                                text: contenidoQR,
                                width: 128,
                                height: 128
                            };

                            // Genera el código QR en el contenedor con el id "qrcode"
                            var qrcode = new QRCode(document.getElementById("qrcode"), opcionesQR);
                        </script>
                    </td>
                    <?php
                    echo '</tr>';
            
                    echo '</table>';
                    echo '<br><br>';

                }
            
                
            } else {
                echo 'No hay salas.';
            }
            
            ?>




            </div>
        </div>
    
        <div id="sala_editar" class="content">
            <h2>Contenido para editar salas</h2>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
        </div>
    
        <div id="sala_eliminar" class="content">
            <h2>Contenido para eliminar salas</h2>
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