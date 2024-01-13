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

        .tabla, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
    </head>
    <body>
        <div class="salas">
            <div id="sala_editar" class="content">
                <h2>Tu sala</h2>
                <div class="contenedor-tablas">
                    <!-- Primera tabla -->
                <?php
                $consulta_salas = mysqli_query($conexion, "SELECT * FROM room where id='$id'");
                while ($row = mysqli_fetch_array($consulta_salas)):
                ?>
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
                                <td><?= $row['roomname'] ?></td>
                            </tr>
                            <tr>
                                <td>Descripcion</td>
                                <td><?= $row['description'] ?></td>
                            </tr>
                            <tr>
                                <td>vidas</td>
                                <td><?= (($row['lives'] == -1)? "ilimitadas" : $row['lives'])     ?></td>
                            </tr>
                            <tr>
                                <td>Pistas</td>
                                <td><?=  (($row['clue'] == 1)? "Si" : "No") ?></td>
                            </tr>
                            <tr>
                                <td>pista despues de:
                                </td>
                                <td><?php echo $row['clueafter'] ?> intentos</td>
                            </tr>
                            <!-- Agrega más filas según sea necesario -->
                        </tbody>
                    </table>




                    <!-- Segunda tabla -->
                    <table class="tabla">
                        <caption>Tabla 2</caption>
                        <thead>
                            <tr>
                                <th>Encabezado A</th>
                                <th>Encabezado B</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dato A-1</td>
                                <td>Dato A-2</td>
                            </tr>
                            <!-- Agrega más filas según sea necesario -->
                        </tbody>
                    </table>
                <?php 
                endwhile; 
                ?>
                </div>
            </div>
            
        </div>
    </body>
</html>