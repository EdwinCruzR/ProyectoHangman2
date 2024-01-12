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
        <title>Eliminar</title>
    </head>
    <body>
    <?php
    function redirectToDashpage($successMessage) {
        echo "<script> alert('$successMessage'); </script>";
        header("Location: ./dashpage.php");
        exit();
    }

    $opcion = isset($_GET['select']) ? $_GET['select'] : null;

    switch ($opcion) {
        case 'sala':
            $cod = $_GET['id'];
            mysqli_query($conexion, "DELETE FROM room WHERE id=$cod");
            redirectToDashpage('Eliminado con exito');
            break;
        case 'lista':
            $cod = $_GET['id'];
            mysqli_query($conexion, "DELETE FROM lists WHERE id=$cod");
            redirectToDashpage('Eliminado con exito');
            break;
        case 'palabra':
            $cod = $_GET['id'];
            mysqli_query($conexion, "UPDATE words SET isactive=0 WHERE id=$cod");
            redirectToDashpage('Eliminado con exito');

            break;
        default:
            echo "no existe";
            //crear el error 404 jsjsjs
            break;
    }
?>
<script src="../assets/js/editarsalas.js"></script>
</body>
</html>