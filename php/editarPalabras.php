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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="./styledash.css">
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="./salas.css">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <title>Editar</title>
</head>
<body>
<div id="sala_palabras" class="content">
            <h2>Contenido para editar palabras</h2>
            <?php
            $id = $_GET['id'];
            $consulta_salas = mysqli_query($conexion, "SELECT * FROM words where id ='$id'");
                      
            while ($row = mysqli_fetch_array($consulta_salas)):
            ?>
            <form id="gameForm" action="./dashpage.php" method="post">
                  <label class="form-label" for="roomName">Nombre de la palabra:</label>
                  <input class="form-input" type="text" id="wordName" name="wordName" maxlength="50" required>

                  <label class="form-label">Seleccione el tipo de verbo:</label>
                  <select class="select-input" id="wordListSelect">
                  <option value="list1">Regular</option>
                  <option value="list2">Irregular</option>
                  </select>

                  <label class="form-label" for="wordClue">Pista de la palabra:</label>
                  <textarea class="form-input form-textarea" id="clue" name="clue" maxlength="300" required></textarea>

                  <label class="form-label" for="simplePastWord">Pasado simple de la palabra:</label>
                  <input class="form-input" type="text" id="wordPast" name="wordPast" maxlength="50" required>

                  <label class="form-label" for="egWord">Ejemplo de la palabra:</label>
                  <textarea class="form-input form-textarea" id="eg" name="eg" maxlength="300" required></textarea>

                  <input type="submit" class="form-button" name="submit_crear_palabra" value="Crear palabra" required>
                  </form>
            <?php endwhile; ?>
            <p>Este es el contenido que se mostrará cuando se seleccione la Opción 3.</p>
</div>
<?php
	
	if(isset($_POST['submit_crear_palabra'])){

        $word = $_POST['wordName'];
        $type = $_POST[''];
        $clue = $_POST['clue'];
        $wordPast = $_POST['wordPast'];
        $eg = $_POST['eg'];
        
        $insertCreate = mysqli_query($conexion,"UPDATE words SET word='$word', type= '$type', clue='$clue', simplepast='$wordPast', example='$eg' WHERE id=$id");
        
        if(mysqli_num_rows($insertCreate) !=0 ){
          echo "<div class='alert alert-danger' role='alert'>
          Error al editar la palabra
        </div>";
          echo "<div class='d-grid gap-2 d-md-flex justify-content-md-center'>
          <a href='javascript:self.history.back()'><button type='button' class='btn btn-danger txt-center'>Atras</button></a>
        </div>";
        }

    }
?>
</body>
</html>