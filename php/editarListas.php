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
    <title>Editar Lista</title>
</head>

<body>
    <div class="listas">
        <div id="listas_crear" class="content">
            <div class="form-container">
                <h1>Editar Lista</h1>
                <?php
            $id = $_GET['id'];

            $consulta_word_up = mysqli_query($conexion, "SELECT * FROM lists WHERE id=$id");

            while ($row = mysqli_fetch_array($consulta_word_up)):
            ?>
                <form id="gameForm" method="post">
                    <label class="form-label" for="listName">Nombre de la Lista:</label>
                    <input class="form-input" type="text" id="listName" name="listName" maxlength="50" value="<?= $row['listname'] ?>" required>

                    <label class="form-label" for="descripcion">Descipcion de la lista</label>
                    <textarea class="form-input form-textarea" id="descripcion" name="descripcion" maxlength="300"  required ><?= $row['description'] ?></textarea>
                    <input class="form-input" type="hidden" id="id" name="id"  value="<?= $row['id'] ?>">
                    <input type="submit" class="form-button" name="submit_editar_lista" value="Editar lista" required>
                </form>
            <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['submit_editar_lista'])) {

        $list = $_POST['listName'];
        $description = $_POST['descripcion'];
        $id = $_POST['id'];

        $insertUpdateWord = mysqli_query($conexion, "UPDATE lists SET listname='$list', description= '$description' WHERE id=$id");

        if(!($insertUpdateWord)){
            echo "<script> alert('Error al editar'); </script>";
            }else{
                
              
              header("Location: ./dashpage.php");
      echo "<script> alert('Se edito correctamente'); </script>";
            }

    }

    ?>
</body>

</html>