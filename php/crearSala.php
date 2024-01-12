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
    <title>Crear Sala</title>
</head>

<body>
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
        </div>
    </div>
<?php
    if (isset($_POST['submit_crear_sala'])) {

        function makeRoomCode()
        {
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

        $qrcode = 'https://www.cbtis150.edu.mx/hangman/php/roomgame.php?r=' . $roomcode;

        // Usa sentencias preparadas para prevenir inyección de SQL
        $insertCreate = mysqli_prepare($conexion, "INSERT INTO room (roomname, description, lives, clue, clueafter, feedback, random, isopen, hasstartdatetime, startdatetime, hasenddatetime, enddatetime, isgeneral, roomcode, qrstring, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertCreate, "ssiiiiiissssssis", $roomName, $roomDescription, $lives, $clue, $clueafter, $feedback, $random, $isopen, $hasstartdatetime, $timestampOpen, $hasenddatetime, $timestampClose, $isgeneral, $roomcode, $qrcode, $id);
        mysqli_stmt_execute($insertCreate);

        /* if(mysqli_stmt_affected_rows($insertCreate) === 0){
            echo 'Error en la creación de la sala: ' . mysqli_error($conexion);
        } else { */
            
header("Location: ./dashpage.php", true);
                    /*    } */

    }

?>

    <script>

        function toggleLivesInput() {
            var numLivesInput = document.getElementById("numLives");
            numLivesInput.disabled = document.getElementById("unlimitedLives").checked;
        }

        function toggleCluesInput() {
            var cluesInputs = document.getElementById("errorNumber");
            if (document.getElementById("showHints").checked) {
                cluesInputs.disabled = false;
            } else {
                cluesInputs.disabled = true;
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