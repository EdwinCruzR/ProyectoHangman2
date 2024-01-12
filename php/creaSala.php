<?php
    session_start();
    include("../bd/conexion.php");
    if (!isset($_SESSION['id'])) {
        header("Location: ../index.php");
        exit();
    }
    $id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/editar.css">
    <title>crear salas</title>
</head>
<body>

<?php 
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

        $roomcode = '';
        do {
            $roomcode = makeRoomCode();
            $verifCode = mysqli_query($conexion, "SELECT roomcode FROM room WHERE roomcode='$roomcode'");
        } while (mysqli_num_rows($verifCode) != 0);

        $qrcode = 'https://www.cbtis150.edu.mx/hangman/php/roomgame.php?r='.$roomcode;

        $insertCreate = mysqli_prepare($conexion, "INSERT INTO room (roomname, description, lives, clue, clueafter, feedback, random, isopen, hasstartdatetime, startdatetime, hasenddatetime, enddatetime, isgeneral, roomcode, qrstring, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertCreate, "ssiiiiiissssssss", $roomName, $roomDescription, $lives, $clue, $clueafter, $feedback, $random, $isopen, $hasstartdatetime, $timestampOpen, $hasenddatetime, $timestampClose, $isgeneral, $roomcode, $qrcode, $id);
        mysqli_stmt_execute($insertCreate);

        if(!($insertCreate)){
            echo "<script> alert('Error al crear la sala'); </script>";
        }else{
            echo "<script> alert('Se creo correctamente'); </script>";
            header("Location: ./dashpage.php");
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
                $consulta_lists = mysqli_query($conexion,"SELECT * FROM lists WHERE user_id=$id"); 
                ?>

                <div class="word-list" id="wordList">
                    <label class="form-label" for="wordListSelect">Seleccione la lista de palabras:</label>
                    <select class="select-input" id="wordListSelect">
                        
                    <?php
                    if ($consulta_lists->num_rows > 0) {
                        while ($row = mysqli_fetch_array($consulta_lists)){ ?>
                            <option value="<?= $row['id'] ?>"><?= $row['listname'] ?></option>
                        <?php } 
                    }else {
                        ?> <option value="sinLis">Sin listas disponibles</option> <?php
                    }
                    ?>
                    </select>
                </div>
                <input type="submit" class="form-button" name="submitBuiltRoom" id="submitBuiltRoom" value="Crear sala" required>
            </form>
        </div>
    </div>
</div>
<script src="../assets/js/salas.js"></script>
</body>
</html>