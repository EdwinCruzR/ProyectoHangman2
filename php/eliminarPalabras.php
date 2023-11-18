<?php
session_start();

include("../bd/conexion.php");
if(!isset($_SESSION['id'])){
 header("Location: ../index.php");
}
 
$cod = $_GET['id'];
 
mysqli_query($conexion, "DELETE FROM words WHERE id=$cod");
 
header("Location:dashpage.php");

?>