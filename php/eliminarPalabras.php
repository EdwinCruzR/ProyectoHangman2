<?php
session_start();

include("../bd/conexion.php");
if(!isset($_SESSION['id'])){
 header("Location: ../index.php");
}
 
$cod = $_GET['id'];
 
mysqli_query($conexion, "UPDATE words SET isactive=0 WHERE id=$id");
 
header("Location:dashpage.php");

?>