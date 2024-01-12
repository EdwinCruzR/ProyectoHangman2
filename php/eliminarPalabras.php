<?php

session_start();

include("../bd/conexion.php");
if(!isset($_SESSION['id'])){
 header("Location: ../index.php");
}
$iduser = $_SESSION['id'];

$cod = $_GET['id'];
 
mysqli_query($conexion, "UPDATE words SET isactive=0 WHERE id=$cod");
 
header("Location:dashpage.php");

?>