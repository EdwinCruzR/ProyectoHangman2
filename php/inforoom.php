<?php
session_start();

include("../bd/conexion.php");
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
}
$iduser = $_SESSION['id'];
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/editar.css">
    <title>Tu sala</title>
    <style>
        .contenedor-tablas {
            display: flex;
            justify-content: space-between;
        }

        .tabla {
            border-collapse: collapse;
            width: 50%;
            margin: 10px;
        }

        thead {
            background-color: grey;
        }

        .tabla,
        th,
        td {
            border: 1px solid black;
            padding: 9px;
            text-align: left;
        }
    </style>
</head>

<body>
<a href="./dashpage.php"><button type="button" class="btn btn-danger regresar">Regresar</button></a>
    <div class="salas">
        <div id="sala_editar" class="content">
            <h2>Tu sala</h2>
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
                    <table class="tabla">
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
                                <td><?= $roomcode ?></td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>
                                    <?= $roomname ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Jugadores que participaron</td>
                                <td><?= $numero_filas_jugadores ?></td>
                            </tr>
                            <tr>
                                <td>Numero de palabras</td>
                                <td><?= $numero_filas_palabras ?></td>
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
                                        colorDark : "#000000",
                                        colorLight : "#ffffff",
                                        correctLevel : QRCode.CorrectLevel.L
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
                                <th>acciones</th>
                            </tr>
                        </thead>
                        <tbody id="players" class="table-group-divider">
                            <?php 
                            $posi = 0;
                            $consulta_players = mysqli_query($conexion, "SELECT * FROM gameroom JOIN users ON gameroom.user_id = users.id WHERE gameroom.room_id = $roomid ORDER BY score DESC");
                            // $consulta_players = mysqli_query($conexion, "SELECT * FROM gameroom WHERE room_id = $roomid ORDER BY score DESC");
                            while ($row = mysqli_fetch_array($consulta_players)):
                                $posi++;
                                $idplayer = $row['user_id'];
                                $nombre = $row['name'];
                                $score = $row['score'];
                                $tiempo = $row['totaltime'];
                            ?>
                            <tr>
                                <td><?= $posi ?></td>
                                <td><?= $nombre ?></td>
                                <td><?= $score ?></td>
                                <td><?= $tiempo ?></td>
                                <td>
                                    <a href="#" class="users-table--more" >Ver mas</a>
                                    <!-- aqui que muesre un modal con otra tabla de palabras  -->
                                </td>
                            </tr>
                            <?php 
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                    </div>
                    </div>
                    <a href="#"><button type="button" class="btn btn-success regresar">lista de palabras</button></a>
                    <a href="#"><button type="button" class="btn btn-success regresar">palabras ordenadas </button></a>
                    <a href="#"><button type="button" class="btn btn-success regresar">palabras que no le aparecieron</button></a>
                </div>
            </div>

    </div>
</body>

</html>