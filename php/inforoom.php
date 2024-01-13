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
            padding: 8px;
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
                                <td>Nombre</td>
                                <td>
                                    <?= $row['roomname'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Descripcion</td>
                                <td>
                                    <?= $row['description'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Vidas</td>
                                <td>
                                    <?= (($row['lives'] == -1) ? "ilimitadas" : $row['lives']) ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Pistas</td>
                                <td>
                                    <?= (($row['clue'] == 1) ? "Si" : "No") ?>
                                </td>
                            </tr>
                            <tr>
                                <td>pista despues de:
                                </td>
                                <td>
                                    <?php echo $row['clueafter'] ?> intentos
                                </td>
                            </tr>
                            <tr>
                                <td>Retroalimentación
                                </td>
                                <th>
                                    <?= (($row['feedback'] == 1) ? "Si" : "No") ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Random
                                </td>
                                <th>
                                    <?= (($row['random'] == 1) ? "Si" : "No") ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Lista
                                </td>
                                <th>
                                    <?= (($row['isgeneral'] == 1) ? "Sistema" : "Lista") ?>
                                </th>
                            </tr>
                            <tr>
                                <td>Abierto
                                </td>
                                <th>
                                    <?= (($row['isopen'] == 1) ? "Si" : "No") ?>
                                </th>
                            </tr>
                            <!-- Agrega más filas según sea necesario -->
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
                                <th>Avatar</th>
                                <th>Nombre</th>
                                <th>Puntos</th>
                                <th>Tiempo</th>
                                <th>Observ</th>
                            </tr>
                        </thead>
                        <tbody id="players" class="table-group-divider">
                            <tr>
                                <td>dad</td>
                            </tr>
                        </tbody>
                    </table>
                <?php
                endwhile;
                ?>
                </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>