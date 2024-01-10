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
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/editar.css">
    <title>Editar</title>
</head>
<body>
<main>
<div class="salas">
<div id="sala_editar" class="content">
            <h2>Contenido para editar salas</h2>
            <?php
            $id = $_GET['id'];
            $consulta_salas = mysqli_query($conexion, "SELECT * FROM room where id='$id'");
                      
            while ($row = mysqli_fetch_array($consulta_salas)):
            ?>
            <form id="gameForm"  method="post">
                  <label class="form-label" for="roomName">Nombre de la sala:</label>
                  <input class="form-input" type="text" id="roomName" name="roomName" maxlength="50" required value="<?= $row['roomname'] ?>">

                  <label class="form-label" for="roomDescription">Descripción de la sala:</label>
                  <textarea class="form-input form-textarea" id="roomDescription" name="roomDescription" maxlength="300" required><?= $row['description'] ?></textarea>

                  <label class="form-label" for="unlimitedLives">¿Vidas ilimitadas?</label>
                  <input class="checkbox-input" type="checkbox" id="unlimitedLives" name="unlimitedLives" onclick="toggleLivesInput()">

                  <label class="form-label" for="numLives">Número de vidas:</label>
                  <input class="form-input" type="number" id="numLives" name="numLives" min="1" max="10" value="<?= $row['lives'] ?>">

                  <label class="form-label" for="showHints">¿Mostrar pistas?</label>
                  <input class="checkbox-input" type="checkbox" id="showHints" name="showHints" onclick="toggleCluesInput()"checked >

                  <label class="form-label" for="errorNumber">Mostrar pistas después del error número:</label>
                  <input class="form-input" type="number" id="errorNumber" name="errorNumber" min="1" max="5" value="<?= $row['clueafter'] ?>">

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
                      <label class="form-label" for="wordListSelect">Seleccione la lista de palabras:</label>
                      <select class="select-input" id="wordListSelect">
                      <option value="list1">cocina</option>
                      <option value="list2">viajes</option>
                      <option value="list3">guerra</option>
                      </select>
                  </div>
                  <input type="submit" class="form-button" name="submit_editar_sala" value="Editar Sala" required>
            </form>
            <?php endwhile; ?>
</div>
<?php
	
	if(isset($_POST['submit_editar_sala']))
{    
    $roomName = $_POST['roomName'];
    $roomDescription = $_POST['roomDescription'];
    $lives = (isset($_POST["unlimitedLives"]) && $_POST["unlimitedLives"] == "on")? -1 : intval($_POST['numLives']);
    $clue = (isset($_POST["showHints"]) && $_POST["showHints"] == "on")? 1 : 0;
    $clueafter = (isset($_POST["showHints"]) && $_POST["showHints"] == "on")? intval($_POST['errorNumber']) : -1;
    $feedback = (isset($_POST["showFeedback"]) && $_POST["showFeedback"] == "on")? 1 : 0 ;
    $isopen = (isset($_POST["isOpen"]) && $_POST["isOpen"] == "on")? 1 : 0 ;
    $random = (isset($_POST["randomOrder"]) && $_POST["randomOrder"] == "on")? 1 : 0 ;
      
    $querymodificar = mysqli_query($conexion, "UPDATE room SET roomname='$roomName', description= '$roomDescription', lives='$lives', clue='$clue', clueafter='$clueafter', feedback='$feedback', random='$random', isopen='$isopen' WHERE id=$id");

    if(!($querymodificar)){
      echo "<script> alert('Error al editar'); </script>";
      }else{
          
        
        header("Location: ./dashpage.php");
echo "<script> alert('Se edito correctamente'); </script>";
      }
    
}
?>
</div>
</main>
</body>
</html>