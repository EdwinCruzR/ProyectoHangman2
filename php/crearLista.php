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
    <title>Crear Lista</title>
</head>

<body>
    <div class="listas">
        <div id="listas_crear" class="content">
            <div class="form-container">
                <h1>Crear Lista</h1>

                <form id="gameForm" method="post">
                    <label class="form-label" for="listName">Nombre de la Lista:</label>
                    <input class="form-input" type="text" id="listName" name="listName" maxlength="50" required>

                    <label class="form-label" for="descripcion">Descipcion de la lista</label>
                    <textarea class="form-input form-textarea" id="descripcion" name="descripcion" maxlength="300"
                        required></textarea>

                    <input type="submit" class="form-button" name="submit_crear_lista" value="Crear lista" required>
                </form>

            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['submit_crear_lista'])) {

        $list = $_POST['listName'];
        $description = $_POST['descripcion'];


        $insertCreate = mysqli_query($conexion, "INSERT INTO lists (listname, description, user_id) VALUES ('$list', '$description', '$iduser')");

        if(!($insertCreate)){
            echo "<script> alert('Error al crear'); </script>";
            }else{
                
              
              header("Location: ./dashpage.php");
      echo "<script> alert('Se creo correctamente'); </script>";
            }     

    }

    ?>
</body>

</html>