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
        <title>crear</title>
    </head>
    <body>
    <?php
    function redirectToDashpage($successMessage) {
        header("Location: ./dashpage.php");
        echo "<script> alert('$successMessage'); </script>";
        exit();
    }

    $opcion = isset($_GET['select']) ? $_GET['select'] : null;

    switch ($opcion) {
        case 'sala':

            if(isset($_POST['submitBuiltRoom'])){

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
                        $timestampOpen = $_POST["timestampOpen"];
                        break;
                    case 'hasenddatetime':
                        $hasstartdatetime = 0;
                        $hasenddatetime = 1;
                        $timestampClose = $_POST["timestampClose"];
                        $timestampOpen = NULL;
                        break;
                    case 'setTime':
                        $hasstartdatetime = 1;
                        $hasenddatetime = 1;
                        $timestampOpen = $_POST["timestampOpen"];
                        $timestampClose = $_POST["timestampClose"];
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
                $islist = (isset($_POST["wordListSelect"]) && $_POST["wordListSelect"] != "0") ? intval($_POST['wordListSelect']) : 0;              

                $roomcode = '';
                do {
                    $roomcode = makeRoomCode();
                    $verifCode = mysqli_query($conexion, "SELECT roomcode FROM room WHERE roomcode='$roomcode'");
                } while (mysqli_num_rows($verifCode) != 0);

                $qrcode = 'https://www.cbtis150.edu.mx/hangman/php/roomgame.php?r='.$roomcode;

                $insertCreate = mysqli_prepare($conexion, "INSERT INTO room (roomname, description, lives, clue, clueafter, feedback, random, isopen, hasstartdatetime, startdatetime, hasenddatetime, enddatetime, isgeneral, roomcode, qrstring, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($insertCreate, "ssiiiiiissssssss", $roomName, $roomDescription, $lives, $clue, $clueafter, $feedback, $random, $isopen, $hasstartdatetime, $timestampOpen, $hasenddatetime, $timestampClose, $isgeneral, $roomcode, $qrcode, $iduser);
                mysqli_stmt_execute($insertCreate);

                $consulta_nuevaID = mysqli_query($conexion, "SELECT id FROM room WHERE roomcode = '$roomcode'");
                if($consulta_nuevaID) {
                    if($row = mysqli_fetch_assoc($consulta_nuevaID)) {
                        $nuevaID = $row['id'];
                    }
                    mysqli_free_result($consulta_nuevaID);
                } else {
                    echo "Error en la consulta de la nueva id: " . mysqli_error($conexion);
                }
                if($isgeneral == 1){
                    $consultaWordsSystem = mysqli_query($conexion, "SELECT id FROM words WHERE user_id = 1");

                    $wordsOfSsystem = array();
                    while($row = mysqli_fetch_array($consultaWordsSystem)) {
                        $wordsOfSsystem[] = $row['id'];
                    }

                    foreach($wordsOfSsystem as $idPalabra) {
                        $insertRHW = mysqli_query($conexion, "INSERT INTO room_has_word (room_id, word_id) VALUES ($nuevaID, $idPalabra)");
                    }
                }else{
                    $consultaWordsList = mysqli_query($conexion, "SELECT word_id FROM list_has_word WHERE list_id = $islist");
                    
                    $wordsOfList = array();
                    while($row = mysqli_fetch_array($consultaWordsList)) {
                        $wordsOfList[] = $row['word_id'];
                    }
                    
                    foreach($wordsOfList as $idWord) {
                        $insertRHW = mysqli_query($conexion, "INSERT INTO room_has_word (room_id, word_id) VALUES ($nuevaID, $idWord)");
                    }
                }

                if(!($insertCreate)){
                    echo "<script> alert('Error al crear la sala'); </script>";
                }else{
                    redirectToDashpage('Se creó correctamente');
                }

            }
            ?>
            <div class="salas">
                <div id="sala_crear" class="content">
                    <div class="form-container">
                        <h1>Crear Sala de Juego</h1>
                        <form id="gameForm" method="post">
                            <label class="form-label" for="roomName">Nombre de la sala:</label>
                            <input class="form-input" type="text" id="roomName" name="roomName" maxlength="50" required>

                            <label class="form-label" for="roomDescription">Descripción de la sala:</label>
                            <textarea class="form-input form-textarea" id="roomDescription" name="roomDescription"
                                maxlength="300" required></textarea>

                            <label class="form-label" for="unlimitedLives">¿Vidas ilimitadas?</label>
                            <input class="checkbox-input" type="checkbox" id="unlimitedLives" name="unlimitedLives"
                                onclick="toggleLivesInput()" checked>

                            <label class="form-label" for="numLives">Número de vidas:</label>
                            <input class="form-input" type="number" id="numLives" name="numLives" min="1" max="10" value="3"
                                disabled>

                            <label class="form-label" for="showHints">¿Mostrar pistas?</label>
                            <input class="checkbox-input" type="checkbox" id="showHints" name="showHints"
                                onclick="toggleCluesInput()" checked>

                            <label class="form-label" for="errorNumber">Mostrar pistas después del error número:</label>
                            <input class="form-input" type="number" id="errorNumber" name="errorNumber" min="1" max="5"
                                value="3">

                            <label class="form-label" for="showFeedback">¿Mostrar retroalimentación?</label>
                            <input class="checkbox-input" type="checkbox" id="showFeedback" name="showFeedback" checked>

                            <label class="form-label" for="randomOrder">¿Orden de palabras aleatorio?</label>
                            <input class="checkbox-input" type="checkbox" id="randomOrder" name="randomOrder">

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
                                <input class="form-input" type="datetime-local" id="timestampOpen" name="timestampOpen">
                            </div>
                            <div class="settimeclose" id="settimeclose">
                                <label class="form-label" for="timestampClose">Hora de cierre:</label>
                                <input class="form-input" type="datetime-local" id="timestampClose" name="timestampClose">
                            </div>

                            <label class="form-label" for="wordSource">Palabras de la sala:</label>
                            <select class="select-input" id="wordSource" name="wordSource" onchange="toggleWordList()">
                                <option value="system">Palabras del sistema</option>
                                <option value="teacher">Palabras del docente</option>
                            </select>

                            <?php 
                            $consulta_lists = mysqli_query($conexion,"SELECT * FROM lists WHERE user_id=$iduser"); 
                            ?>

                            <div class="word-list" id="wordList">
                                <label class="form-label" for="wordListSelect">Seleccione la lista de palabras:</label>
                                <select class="select-input" id="wordListSelect" name="wordListSelect">
                                    <option value="0">Selecciona una lista</option>
                                    
                                <?php
                                if ($consulta_lists->num_rows > 0) {
                                    while ($row = mysqli_fetch_array($consulta_lists)){ ?>
                                        <option value="<?= $row['id'] ?>"><?= $row['listname'] ?></option>
                                    <?php } 
                                }else {
                                    ?> <option value="0">Sin listas disponibles</option> <?php
                                }
                                ?>
                                </select>
                            </div>
                            <input type="submit" class="form-button" name="submitBuiltRoom" id="submitBuiltRoom" value="Crear sala" required>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            break;
        case 'lista':
            ?>
            <div class="listas">
                <div id="listas_crear" class="content">
                    <div class="form-container">
                        <h1>Crear Lista</h1>
                        <form id="gameForm" method="post">
                            <label class="form-label" for="listName">Nombre de la Lista:</label>
                            <input class="form-input" type="text" id="listName" name="listName" maxlength="50" required>
                            <label class="form-label" for="descripcion">Descipcion de la lista</label>
                            <textarea class="form-input form-textarea" id="descripcion" name="descripcion" maxlength="300" required></textarea>
                            <input type="submit" class="form-button" name="submit_crear_lista" value="Crear lista" required>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            if (isset($_POST['submit_crear_lista'])) {
                $list = $_POST['listName'];
                $description = $_POST['descripcion'];

                $insertCreate = mysqli_prepare($conexion, "INSERT INTO lists (listname, description, user_id) VALUES (?, ?, ?)");

                if ($insertCreate) {
                    mysqli_stmt_bind_param($insertCreate, "ssi", $list, $description, $iduser);
                    $result = mysqli_stmt_execute($insertCreate);

                    if ($result) {
                        redirectToDashpage('Se creó correctamente');
                    } else {
                        echo "<script> alert('Error al crear'); </script>";
                    }

                    mysqli_stmt_close($insertCreate);
                } else {
                    echo "<script> alert('Error en la preparación de la consulta'); </script>";
                }
            }
            break;
        case 'palabra':
            ?>
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
                    redirectToDashpage('Se creó correctamente');
                }           
            }
            break;
            
        default:
            echo "no existe";
            break;
    }
?>
<script src="../assets/js/salas.js"></script>
</body>
</html>