<?php
session_start();

include("../bd/conexion.php");
if (!isset($_SESSION['id'])) {
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
        <title>Editar</title>
    </head>
    <body>
    <a href="./dashpage.php"><button type="button" class="btn btn-danger regresar">Regresar</button></a>
    <?php
    function redirectToDashpage($successMessage) {
        echo "<script> alert('$successMessage'); </script>";
        header("Location: ./dashpage.php");
        exit();
    }

    $opcion = isset($_GET['select']) ? $_GET['select'] : null;

    switch ($opcion) {
        case 'sala':
            ?>
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
                        <input class="checkbox-input" type="checkbox" id="unlimitedLives" name="unlimitedLives" onclick="toggleLivesInput()" <?= (($row['lives']== -1)? 'checked': '' )?> >

                        <label class="form-label" for="numLives">Número de vidas:</label>
                        <input class="form-input" type="number" id="numLives" name="numLives" min="1" max="10" value="<?= (($row['lives']>0)? $row['lives'] : "" )?>">

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
                        <input class="form-input" type="hidden" id="id" name="id"  value="<?= $row['id'] ?>">
                        <input type="submit" class="form-button" name="submit_editar_sala" value="Editar Sala" required>
                    </form>
                    <?php 
                    endwhile; 
                    ?>
                </div>
                <?php
                if(isset($_POST['submit_editar_sala'])){    

                    $roomName = $_POST['roomName'];
                    $roomDescription = $_POST['roomDescription'];
                    $lives = (isset($_POST["unlimitedLives"]) && $_POST["unlimitedLives"] == "on")? -1 : intval($_POST['numLives']);
                    $clue = (isset($_POST["showHints"]) && $_POST["showHints"] == "on")? 1 : 0;
                    $clueafter = (isset($_POST["showHints"]) && $_POST["showHints"] == "on")? intval($_POST['errorNumber']) : -1;
                    $feedback = (isset($_POST["showFeedback"]) && $_POST["showFeedback"] == "on")? 1 : 0 ;
                    $isopen = (isset($_POST["isOpen"]) && $_POST["isOpen"] == "on")? 1 : 0 ;
                    $random = (isset($_POST["randomOrder"]) && $_POST["randomOrder"] == "on")? 1 : 0 ;
                    $id= $_POST['id'];
                    $querymodificar = mysqli_query($conexion, "UPDATE room SET roomname='$roomName', description= '$roomDescription', lives='$lives', clue='$clue', clueafter='$clueafter', feedback='$feedback', random='$random', isopen='$isopen' WHERE id=$id");

                    if(!($querymodificar)){
                    echo "<script> alert('Error al editar'); </script>";
                    }else{
                        redirectToDashpage('Se edito correctamente');
                    }
                
                }
                ?>
            </div>
            <?php
            break;
        case 'lista':
            ?>
            <div class="listas">
                <div id="listas_crear" class="content">
                    <div class="form-container">
                        <h1>Editar Lista</h1>
                        <?php
                        $id = $_GET['id'];

                        $consulta_word_up = mysqli_query($conexion, "SELECT * FROM lists WHERE id=$id");

                        while ($row = mysqli_fetch_array($consulta_word_up)):
                        ?>
                            <form id="gameForm" method="post">
                                <label class="form-label" for="listName">Nombre de la Lista:</label>
                                <input class="form-input" type="text" id="listName" name="listName" maxlength="50" value="<?= $row['listname'] ?>" required>
                                <label class="form-label" for="descripcion">Descipcion de la lista</label>
                                <textarea class="form-input form-textarea" id="descripcion" name="descripcion" maxlength="300"  required ><?= $row['description'] ?></textarea>
                                <input class="form-input" type="hidden" id="id" name="id"  value="<?= $row['id'] ?>">
                                <input type="submit" class="form-button" name="submit_editar_lista" value="Editar lista" required>
                            </form>
                        <?php 
                        endwhile; 
                        ?>
                    </div>
                </div>
            </div>
            <?php
            if (isset($_POST['submit_editar_lista'])) {

                $list = $_POST['listName'];
                $description = $_POST['descripcion'];
                $id = $_POST['id'];

                $insertUpdateWord = mysqli_query($conexion, "UPDATE lists SET listname='$list', description= '$description' WHERE id=$id");

                if(!($insertUpdateWord)){
                    echo "<script> alert('Error al editar'); </script>";
                }else{
                    redirectToDashpage('Se edito correctamente');
                }
            }
            break;
        case 'palabra':
            ?>
            <div class="palabras">
                <div id="sala_palabras" class="content">
                    <h2>Contenido para editar palabras</h2>
                    <?php
                    $id = $_GET['id'];

                    $consulta_word_up = mysqli_query($conexion, "SELECT * FROM words where id = $id");

                    while ($row = mysqli_fetch_array($consulta_word_up)):
                    ?>
                        <form id="gameForm"  method="post">
                            <label class="form-label" for="wordName">Nombre de la palabra:</label>
                            <input class="form-input" type="text" id="wordName" name="wordName" maxlength="50" required value="<?= $row['word'] ?>">

                            <label class="form-label" for="typeListSelect">Seleccione el tipo de verbo:</label>
                            <select class="select-input" id="typeListSelect" name="typeListSelect">
                                <option value="R">Regular</option>
                                <option value="I">Irregular</option>
                            </select>

                            <label class="form-label" for="clue">Pista de la palabra:</label>
                            <textarea class="form-input form-textarea" id="clue" name="clue" maxlength="300" required><?= $row['clue'] ?></textarea>

                            <label class="form-label" for="wordPast">Pasado simple de la palabra:</label>
                            <input class="form-input" type="text" id="wordPast" name="wordPast" maxlength="50" required value="<?= $row['simplepast'] ?>">

                            <label class="form-label" for="eg">Ejemplo de la palabra:</label>
                            <textarea class="form-input form-textarea" id="eg" name="eg" maxlength="300" required><?= $row['example'] ?></textarea>
                            <input class="form-input" type="hidden" id="id" name="id"  value="<?= $row['id'] ?>">
                            <input type="submit" class="form-button" name="submit_editar_palabra" value="Editar palabra" required>
                        </form>
                    <?php 
                    endwhile; 
                    ?>
                </div>
            <?php
                
                if(isset($_POST['submit_editar_palabra'])){

                    $word = $_POST['wordName'];
                    $type = $_POST['typeListSelect'];
                    $clue = $_POST['clue'];
                    $wordPast = $_POST['wordPast'];
                    $eg = $_POST['eg'];
                    $id = $_POST['id'];
                    
                //   UPDATE words SET isactive = b'1', word = 'watafak' WHERE id = 17 AND user_id = 4;
                    $insertUpdateWord = mysqli_query($conexion,"UPDATE words SET word='$word', type= '$type', clue='$clue', simplepast='$wordPast', example='$eg' WHERE id=$id");
                    
                    if(!($insertUpdateWord)){
                        echo "<script> alert('Error al editar'); </script>";
                    }else{
                        redirectToDashpage('Se edito correctamente');
                    }
                }
            ?>
            </div>
            <?php
            break;
        default:
            echo "no existe";
            //crear el error 404 jsjsjs
            break;
    }
?>
<script>
    var checkbox = document.getElementById('unlimitedLives');
      var numLivesInput = document.getElementById('numLives');

        if (checkbox.checked) {
            numLivesInput.disabled = true;
        } else {
            numLivesInput.disabled = false;
        }

    function toggleLivesInput() {
      var checkbox = document.getElementById('unlimitedLives');
      var numLivesInput = document.getElementById('numLives');

        if (checkbox.checked) {
            numLivesInput.disabled = true;
        } else {
            numLivesInput.disabled = false;
        }
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
<!-- <script src="../assets/js/editarsalas.js"></script> -->
</body>
</html>