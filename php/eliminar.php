<?php
session_start();

include("../bd/conexion.php");
if (!isset($_SESSION['id'])) {
    header("Location: ../index.html");
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

            $consultaGRId =  mysqli_query($conexion, "SELECT id FROM gameroom WHERE room_id=$cod");

            $idsGR = array();
            while($row = mysqli_fetch_array($consultaGRId)) {
                $idsGR[] = $row['id'];
            }

            foreach($idsGR as $idSGR) {
                mysqli_query($conexion, "DELETE FROM detailgameroom WHERE gameroom_id=$idSGR");
            }
            
            mysqli_query($conexion, "DELETE FROM gameroom WHERE room_id=$cod");
            mysqli_query($conexion, "DELETE FROM room_has_word WHERE room_id=$cod");
            mysqli_query($conexion, "DELETE FROM room WHERE id=$cod");
            redirectToDashpage('Eliminado con exito');
            break;
        case 'lista':
            $cod = $_GET['id'];

            $consultaWDId =  mysqli_query($conexion, "SELECT word_id FROM list_has_word WHERE list_id=$cod");
            if(mysqli_num_rows($consultaWDId) !=0){
                $idsWR = array();
                while($row = mysqli_fetch_array($consultaWDId)) {
                    $idsWR[] = $row['id'];
                }

                foreach($idsGR as $idSGR) {
                    mysqli_query($conexion, "DELETE FROM room_has_word WHERE word_id=$idSGR");
                }
            }
            mysqli_query($conexion, "DELETE FROM list_has_word WHERE list_id=$cod");
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