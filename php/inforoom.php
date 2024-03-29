<?php
session_start();

include("../bd/conexion.php");
if (!isset($_SESSION['id'])) {
    header("Location: ../index.html");
}
$iduser = $_SESSION['id'];
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/inforoom.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <title>Tu sala</title>

</head>

<body>
    <a href="./dashpage.php"><button type="button" class="btn btn-danger regresar">Regresar</button></a>
    <div class="salas" id="tabla-de-posisciones">
        <div id="sala_editar" class="content">
            <h2>Tu sala</h2>
            <div class="timestamp">
                <h4 id="timestamp"></h4>
            </div>

            <div class="contenedor-tablas">

                <!-- Primera tabla -->
                <?php
                $consulta_salas = mysqli_query($conexion, "SELECT * FROM room where id='$id'");
                while ($row = mysqli_fetch_array($consulta_salas)):
                    $roomid = $row['id'];
                    $roomcode = $row['roomcode'];
                    $qrstring = $row['qrstring'];
                    $roomname = $row['roomname'];
                    $isopen = $row['isopen'];
                    $timestamp = $row['timestamp'];
                    $hasstartdatetime = $row['hasstartdatetime'];
                    $startdatetime = $row['startdatetime'];
                    $hasenddatetime = $row['hasenddatetime'];
                    $enddatetime = $row['enddatetime'];
                endwhile;

                $consulta_gameroom = mysqli_query($conexion, "SELECT * FROM gameroom where room_id='$roomid'");
                $numero_filas_jugadores = mysqli_num_rows($consulta_gameroom);

                $consulta_rhw = mysqli_query($conexion, "SELECT * FROM room_has_word where room_id='$roomid'");
                $numero_filas_palabras = mysqli_num_rows($consulta_rhw);

                // while ($row = mysqli_fetch_array($consulta_salas)):
                //     $roomcode = $row['roomcode'];
                
                // endwhile;
                ?>
                <div class="tabla1">
                    <table>
                        <caption>Info. Sala</caption>
                        <thead>
                            <tr>
                                <th>Dato</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Codigo de sala</td>
                                <td>
                                    <?= $roomcode ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>
                                    <?= $roomname ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Jugadores que participaron</td>
                                <td>
                                    <?= $numero_filas_jugadores ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Numero de palabras</td>
                                <td>
                                    <?= $numero_filas_palabras ?>
                                </td>
                            </tr>
                            <tr>
                                <td>QR</td>
                                <td>
                                    <div id="qrcode"></div>
                                    <script>
                                        var contenidoQR = '<?= $qrstring ?>';
                                        var qrcode = new QRCode(document.getElementById("qrcode"), {
                                            text: contenidoQR,
                                            width: 100,
                                            height: 100,
                                            colorDark: "#000000",
                                            colorLight: "#ffffff",
                                            correctLevel: QRCode.CorrectLevel.L
                                        });
                                    </script>
                                </td>
                            </tr>
                            <tr>
                                <td>Abierto</td>
                                <th>
                                    <?= (($isopen == 1) ? "Si" : "No") ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Creado el:</td>
                                <th>
                                    <?= $timestamp ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Hora de apertura?</td>
                                <th>
                                    <?= (($hasstartdatetime == 1) ? "Si" : "No") ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Hora apertura</td>
                                <th>
                                    <?= $startdatetime ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Hora de cierre?</td>
                                <th>
                                    <?= (($hasenddatetime == 1) ? "Si" : "No") ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Hora cierre</td>
                                <th>
                                    <?= $enddatetime ?>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>



                <div class="tabla2">
                    <!-- Segunda tabla -->
                    <div class="container2">
                        <table class="tabla">
                            <caption>Tabla de Posiciones</caption>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Puntos</th>
                                    <th>Tiempo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="players" class="table-group-divider">
                                <?php
                                $posi = 0;
                                $consulta_players = mysqli_query($conexion, "SELECT gameroom.*, users.name FROM gameroom JOIN users ON gameroom.user_id = users.id WHERE gameroom.room_id = $roomid ORDER BY gameroom.score DESC");
                                // $consulta_players = mysqli_query($conexion, "SELECT * FROM gameroom WHERE room_id = $roomid ORDER BY score DESC");
                                while ($row = mysqli_fetch_array($consulta_players)):
                                    $posi++;
                                    $gameroomid = $row['id'];
                                    $idplayer = $row['user_id'];
                                    $nombre = $row['name'];
                                    $score = $row['score'];
                                    $tiempo = $row['totaltime'];
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $posi ?>
                                        </td>
                                        <td>
                                            <?= $nombre ?>
                                        </td>
                                        <td>
                                            <?= $score ?>
                                        </td>
                                        <td>
                                            <?= $tiempo ?>
                                        </td>
                                        <td>
                                            <!-- Agrega el botón para abrir el modal -->
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#modalPalabras<?= $idplayer ?>">
                                                Ver más
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalPalabras<?= $idplayer ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Palabras de
                                                                <?= $nombre ?>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- a -->

                                                            <?php
                                                            $consulta_gameroomdetails = mysqli_query($conexion, "SELECT detailgameroom.*, words.word FROM detailgameroom JOIN words ON detailgameroom.word_id = words.id WHERE detailgameroom.gameroom_id = $gameroomid ORDER BY detailgameroom.pointsperword DESC");

                                                            ?>
                                                            <div class="container3">
                                                                <table class="tabla3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Palabra</th>
                                                                            <th>Adivinada</th>
                                                                            <th>Tipo</th>
                                                                            <th>Pasado</th>
                                                                            <th>Tiempo invertido</th>
                                                                            <th>Puntos acumulados</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        while ($row = mysqli_fetch_array($consulta_gameroomdetails)):
                                                                            ?>
                                                                            <tr>
                                                                                <th>
                                                                                    <?= $row['word'] ?>
                                                                                </th>
                                                                                <!-- al momento de pasar el mouse encima del th de word id que salga una tablita de 2 x 2 
                                                                para poner lo su tipo, pasado simple-->
                                                                                <th>
                                                                                    <?= (($row['guessed'] == 1) ? "Si" : "No") ?>
                                                                                </th>
                                                                                <th>
                                                                                    <?= (($row['typecorrect'] == 1) ? "Correcto" : "Incorrecto") ?>
                                                                                </th>
                                                                                <th>
                                                                                    <?= (($row['pastcorrect'] == 1) ? "Correcto" : "Incorrecto") ?>
                                                                                </th>
                                                                                <th>
                                                                                    <?= $row['timeperword'] ?>
                                                                                </th>
                                                                                <th>
                                                                                    <?= $row['pointsperword'] ?>
                                                                                </th>
                                                                            </tr>
                                                                        <?php endwhile; ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <!-- a -->
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                endwhile;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="botones">
                <a href="#" data-toggle="modal" data-target="#listaPalabrasModal"><button type="button"
                        class="btn btn-success regresar">Lista de palabras</button></a>
                <a href="#" data-toggle="modal" data-target="#FailsType"><button type="button"
                        class="btn btn-success regresar">Palabras falladas por tipo</button></a>
                <a href="#" data-toggle="modal" data-target="#FailsPast"><button type="button"
                        class="btn btn-success regresar">Palabras falladas por pasado</button></a>
                <a href="#" data-toggle="modal" data-target="#palabrasInactivas"><button type="button"
                        class="btn btn-success regresar">Palabras inactivas</button></a>

                <a href="javascript:genPDF()"><button type="button" class="btn btn-success regresar">Descargar
                        PDF</button></a>
            </div>

            <!-- Modales -->
            <div class="modal fade" id="listaPalabrasModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Lista de palabras </h5>
                        </div>
                        <div class="modal-body">
                            <!-- a -->

                            <?php
                            $comsultaRhw = mysqli_query($conexion, "SELECT word_id FROM room_has_word WHERE room_id=$roomid");

                            $wordsRhw = array();
                            while ($row = mysqli_fetch_array($comsultaRhw)) {
                                $wordsRhw[] = $row['word_id'];
                            }
                            ?>
                            <div class="container3">
                                <table class="tabla3">
                                    <thead>
                                        <tr>
                                            <!-- <th>ID</th> -->
                                            <th>Id</th>
                                            <th>Word</th>
                                            <th>Tipo</th>
                                            <th>Pista</th>
                                            <th>Pasado</th>
                                            <th>Ejemplo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($wordsRhw as $idWord) {
                                            $insertRHW = mysqli_query($conexion, "SELECT * FROM words WHERE id=$idWord");
                                            while ($row = mysqli_fetch_array($insertRHW)):
                                                ?>
                                                <tr>
                                                    <th>
                                                        <?= $row['id'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['word'] ?>
                                                    </th>
                                                    <!-- al momento de pasar el mouse encima del th de word id que salga una tablita de 2 x 2 
                                        para poner lo su tipo, pasado simple-->
                                                    <th>
                                                        <?= $row['type'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['clue'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['simplepast'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['example'] ?>
                                                    </th>
                                                </tr>
                                            <?php
                                            endwhile;
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <!-- a -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="modal fade" id="MenuPalabrasFalladas" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Fails</h5>
                            <h6 class="modal-title" id="exampleModalLabel">Selecciona el criterio</h6>
                        </div>
                        <div class="modal-body">
                            <a href="#" data-toggle="modal" data-target="#FailsType" data-dismiss="modal"><button type="button"
                            class="btn btn-success regresar">Tipo</button></a>
                            
                            <a href="#" data-toggle="modal" data-target="#FailsPast" data-dismiss="modal"><button type="button"
                            class="btn btn-success regresar">Pasado</button></a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="modal fade" id="FailsType" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Fails Type</h5>
                            <h6 class="modal-title" id="exampleModalLabel">Mas fallada a la menos fallada</h6>
                        </div>
                        <div class="modal-body">
                            <!-- a -->

                            <?php
                            $comsultaRhw = mysqli_query($conexion, "SELECT words.*, room_has_word.typefails FROM words JOIN room_has_word ON words.id = room_has_word.word_id WHERE room_has_word.room_id = $roomid ORDER BY room_has_word.typefails DESC");
                            ?>
                            <div class="container3">
                                <table>
                                    <thead>
                                        <tr>
                                            <!-- <th>ID</th> -->
                                            <th>Veces fallada</th>
                                            <th>Word</th>
                                            <th>Tipo</th>
                                            <th>Pista</th>
                                            <th>Pasado</th>
                                            <th>Ejemplo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $insertRHW = mysqli_query($conexion, "SELECT words.*, room_has_word.typefails FROM words JOIN room_has_word ON words.id = room_has_word.word_id WHERE room_has_word.room_id = $roomid ORDER BY room_has_word.typefails DESC");
                                            while ($row = mysqli_fetch_array($insertRHW)):
                                                ?>
                                                <tr>
                                                    <th>
                                                        <?= $row['typefails'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['word'] ?>
                                                    </th>
                                                    <!-- al momento de pasar el mouse encima del th de word id que salga una tablita de 2 x 2 
                                        para poner lo su tipo, pasado simple-->
                                                    <th>
                                                        <?= $row['type'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['clue'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['simplepast'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['example'] ?>
                                                    </th>
                                                </tr>
                                            <?php
                                            endwhile;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- a -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="FailsPast" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Fails Past</h5>
                            <h6 class="modal-title" id="exampleModalLabel">Mas fallada a la menos fallada</h6>
                        </div>
                        <div class="modal-body">
                            <!-- a -->

                            <?php
                            $comsultaRhw = mysqli_query($conexion, "SELECT words.*, room_has_word.pastfails FROM words JOIN room_has_word ON words.id = room_has_word.word_id WHERE room_has_word.room_id = $roomid ORDER BY room_has_word.pastfails DESC");
                            ?>
                            <div class="container3">
                                <table class="tabla3">
                                    <thead>
                                        <tr>
                                            <!-- <th>ID</th> -->
                                            <th>Veces fallada</th>
                                            <th>Word</th>
                                            <th>Tipo</th>
                                            <th>Pista</th>
                                            <th>Pasado</th>
                                            <th>Ejemplo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $insertRHW = mysqli_query($conexion, "SELECT words.*, room_has_word.pastfails FROM words JOIN room_has_word ON words.id = room_has_word.word_id WHERE room_has_word.room_id = $roomid ORDER BY room_has_word.pastfails DESC");
                                            while ($row = mysqli_fetch_array($insertRHW)):
                                                ?>
                                                <tr>
                                                    <th>
                                                        <?= $row['pastfails'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['word'] ?>
                                                    </th>
                                                    <!-- al momento de pasar el mouse encima del th de word id que salga una tablita de 2 x 2 
                                        para poner lo su tipo, pasado simple-->
                                                    <th>
                                                        <?= $row['type'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['clue'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['simplepast'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['example'] ?>
                                                    </th>
                                                </tr>
                                            <?php
                                            endwhile;
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <!-- a -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="palabrasInactivas" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Inactividad</h5>
                            <h6 class="modal-title" id="exampleModalLabel">Mas inactiva a la menos inactiva</h6>
                        </div>
                        <div class="modal-body">
                            <!-- a -->

                            <?php
                            $comsultaRhw = mysqli_query($conexion, "SELECT words.*, room_has_word.used FROM words JOIN room_has_word ON words.id = room_has_word.word_id WHERE room_has_word.room_id = $roomid ORDER BY room_has_word.used ASC");
                            ?>
                            <div class="container3">
                                <table class="tabla3">
                                    <thead>
                                        <tr>
                                            <!-- <th>ID</th> -->
                                            <th>Veces usada</th>
                                            <th>Word</th>
                                            <th>Tipo</th>
                                            <th>Pista</th>
                                            <th>Pasado</th>
                                            <th>Ejemplo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $insertRHW = mysqli_query($conexion, "SELECT words.*, room_has_word.used FROM words JOIN room_has_word ON words.id = room_has_word.word_id WHERE room_has_word.room_id = $roomid ORDER BY room_has_word.used ASC");
                                            while ($row = mysqli_fetch_array($insertRHW)):
                                                ?>
                                                <tr>
                                                    <th>
                                                        <?= $row['used'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['word'] ?>
                                                    </th>
                                                    <!-- al momento de pasar el mouse encima del th de word id que salga una tablita de 2 x 2 
                                        para poner lo su tipo, pasado simple-->
                                                    <th>
                                                        <?= $row['type'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['clue'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['simplepast'] ?>
                                                    </th>
                                                    <th>
                                                        <?= $row['example'] ?>
                                                    </th>
                                                </tr>
                                            <?php
                                            endwhile;
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                            <!-- a -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>

    </div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.js"></script>
    <script type="text/javascript">
        function genPDF() {
            const timestamp = new Date();
            let mostrar = document.getElementById('timestamp');
            mostrar.innerHTML = timestamp.getHours() + ":" + timestamp.getMinutes() + ":" + timestamp.getSeconds() + "</br>" + timestamp.getDate() + "/" + (timestamp.getMonth() + 1) + "/" + timestamp.getFullYear();
            var maintable = document.getElementById('tabla-de-posisciones')
            var doc = new jsPDF('p', 'pt', 'letter');
            var margin = 20;
            var scale = (doc.internal.pageSize.width - margin * 2) / document.body.clientWidth;
            var scale_movile = (doc.internal.pageSize.width - margin * 2) / document.body.getBoundingClientRect();
            if (/Andorid|webOS|IPhone|IPad/i.test(navigator.userAgent)) {
                doc.html(maintable, {
                    x: margin,
                    y: margin,
                    html2canvas: {
                        scale: scale_mobile,
                    },
                    callback: function (doc) {
                        doc.save('Tabla_de_Posiciones.pdf')
                    }
                });
            } else {
                doc.html(maintable, {
                    x: margin,
                    y: margin,
                    html2canvas: {
                        scale: scale,
                    },
                    callback: function (doc) {
                        doc.save('Tabla_de_Posiciones.pdf')
                    }
                });
            }
            setTimeout(function () {
                mostrar.innerHTML = "";
            }, 1);
        }


    </script>
</body>

</html>