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
            <li><a href="./creaSala.php">Crear</a></li>
            <li><a href="#sala_consultar" onclick="toggleContent('sala_consultar')">Colsultar</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Palabras
          </a>
          <ul class="submenu dropdown-menu">
            <li><a href="./crearPalabra.php" >Crear</a></li>
            <li><a href="#palabras_consultar" onclick="toggleContent('palabras_consultar')">Colsultar</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Listas
          </a>
          <ul class="submenu dropdown-menu">
            <li><a href="./crearLista.php" >Crear</a></li>
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
                <?php 
                  $num = 0;
                  while ($row = mysqli_fetch_array($consulta_salas)): 
                  $num = $num + 1;
                  ?>
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
                        <div id="qrcode-<?= $num ?>"></div>

                        <script>
                            var contenidoQR = '<?php echo $row['qrstring']; ?>';

                            var qrcode = new QRCode(document.getElementById("qrcode-<?= $num ?>"), {
                                text: contenidoQR,
                                width: 128,
                                height: 128,
                                colorDark : "#000000",
                                colorLight : "#ffffff",
                                correctLevel : QRCode.CorrectLevel.L
                            });

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
    </div>
    <div class="palabras">
  
        <div id="palabras_consultar" class="content">
            <h2>Tus palabras</h2>
            <div class="contenido">

            <?php 
            $consulta_words = mysqli_query($conexion,"SELECT * FROM words WHERE user_id=$id AND isactive=1");
                    
            if ($consulta_words->num_rows > 0) {
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
                <?php while ($row = mysqli_fetch_array($consulta_words)): ?>
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

    <div class="listas">
    
        <div id="listas_consultar" class="content">
            <h2>Tus listas</h2>
            <div class="contenido">

            <?php 
            $consulta_salas = mysqli_query($conexion,"SELECT * FROM lists");
                    
            if ($consulta_salas->num_rows > 0) {
            ?>

            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de la lista</th>
                    <th>Descripcion</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($consulta_salas)): ?>
                    <tr>
                        <th><?= $row['id'] ?></th>
                        <th><?= $row['listname'] ?></th>
                        <th><?= $row['description'] ?></th>
                        <th><a href="editarListas.php?id=<?= $row['id'] ?>" class="users-table--edit">Editar</a><br>
                        <th><a href="addWords.php?id=<?= $row['id'] ?>" class="users-table--edit">Añadir palabras</a><br>
                        <a href="eliminarListas.php?id=<?= $row['id'] ?>" onClick="return confirm('¿Estás seguro de eliminar a <?php echo $row['id']; ?>')" class="users-table--delete" >Eliminar</a></th>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php 
            }else {
                echo 'No hay Listas registradas.';
            }
            
        ?>
            </div>
        </div>
    </div>
</main>
<script src="../assets/js/dashpage.js"></script>
  
</body>
</html>