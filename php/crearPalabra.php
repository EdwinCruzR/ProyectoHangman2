<?php 
   session_start();

   include("../bd/conexion.php");
   if(!isset($_SESSION['id'])){
    header("Location: ../index.php");
   }

    $iduser = $_SESSION['id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/editar.css">
    <title>Crear Palabra</title>
</head>
<body>
<div class="palabras">
    <div id="palabras_crear" class="content">
    <div class="form-container">
    <h1>Crear Palabras</h1>

<form id="gameForm" method="post">
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
</div>
</div>

    <?php 
              if(isset($_POST['submit_crear_palabra'])){

                  $word = $_POST['wordName'];
                  $type = $_POST['typeListSelect'];
                  $clue = $_POST['clue'];
                  $wordPast = $_POST['wordPast'];
                  $eg = $_POST['eg'];
                  
                  $insertCreate = mysqli_query($conexion,"INSERT INTO words (word, type, clue, simplepast, example, user_id) VALUES ('$word', '$type','$clue', '$wordPast', '$eg', '$iduser')");
                  
                  if(!($insertCreate)){
                    echo "<script> alert('Error al crear'); </script>";
                    }else{
                        
                      
                      header("Location: ./dashpage.php");
              echo "<script> alert('Se creo correctamente'); </script>";
                    }           

              }
              
              ?>
</body>
</html>