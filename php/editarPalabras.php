<?php 
   include("../bd/conexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/editar.css">
    <title>Editar</title>
</head>
<body>
<main>
<div class="palabras">
<div id="sala_palabras" class="content">
            <h2>Contenido para editar palabras</h2>
            <?php
            $id = $_REQUEST['id'];
            $consulta_salas = mysqli_query($conexion, "SELECT * FROM words where id ='$id'");
                      
            while ($row = mysqli_fetch_array($consulta_salas)):
            ?>
            <form id="gameForm" action="./dashpage.php" method="post">
            <input class="form-input" type="text" id="wordName" name="wordName" maxlength="50" required value="<?= $row['word'] ?>" >
                  <label class="form-label" for="wordName">Nombre de la palabra:</label>
                  <input class="form-input" type="text" id="wordName" name="wordName" maxlength="50" required value="<?= $row['word'] ?>">

                  <label class="form-label" for="typeListSelect">Seleccione el tipo de verbo:</label>
                  <select class="select-input" id="typeListSelect">
                  <option value="list1">Regular</option>
                  <option value="list2">Irregular</option>
                  </select>

                  <label class="form-label" for="clue">Pista de la palabra:</label>
                  <textarea class="form-input form-textarea" id="clue" name="clue" maxlength="300" required><?= $row['clue'] ?></textarea>

                  <label class="form-label" for="wordPast">Pasado simple de la palabra:</label>
                  <input class="form-input" type="text" id="wordPast" name="wordPast" maxlength="50" required value="<?= $row['simplepast'] ?>">

                  <label class="form-label" for="eg">Ejemplo de la palabra:</label>
                  <textarea class="form-input form-textarea" id="eg" name="eg" maxlength="300" required><?= $row['example'] ?></textarea>

                  <input type="submit" class="form-button" name="submit_crear_palabra" value="Editar palabra" required>
                  </form>
            <?php endwhile; ?>
</div>
<?php
	
	if(isset($_POST['submit_crear_palabra'])){

        $word = $_POST['wordName'];
        $type = $_POST[''];
        $clue = $_POST['clue'];
        $wordPast = $_POST['wordPast'];
        $eg = $_POST['eg'];
        
        $insertCreate = mysqli_query($conexion,"UPDATE words SET word='$word', type= '$type', clue='$clue', simplepast='$wordPast', example='$eg' WHERE id=$id");
        
        if(!($querymodificar)){
          echo "<script> alert('Error al editar'); </script>";
          }else{
              
            
            header("Location: ./dashpage.php");
    echo "<script> alert('Se edito correctamente'); </script>";
          }

    }
?>
</div>
</main>
</body>
</html>