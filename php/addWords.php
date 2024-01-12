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
    <title>Añadir Palabras</title>
</head>

<body>
    <div class="listas">
        <div id="listas_crear" class="content">
            <div class="form-container">
                <h1>Añadir palabras Lista</h1>
                <?php
            $id = $_GET['id'];

            $consulta_word_up = mysqli_query($conexion, "SELECT * FROM words WHERE user_id=$iduser AND isactive=1");
            ?>
            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Palabra</th>
                    <th>Tipo</th>
                    <th>Pista</th>
                    <th>Pasado simple</th>
                    <th>Ejemplo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($consulta_word_up)): ?>
                    <tr>
                        <th><?= $row['id'] ?></th>
                        <th><?= $row['word'] ?></th>
                        <th><?= (($row['type'] == "I")? "Irregular" : "Regular") ?></th>
                        <th><?= $row['clue'] ?></th>
                        <th><?= $row['simplepast'] ?></th>
                        <th><?= $row['example'] ?></th>
                        <th><a href="editarPalabras.php?id=<?= $row['id'] ?>" class="users-table--edit">Añadir</a><br>
                        <!-- aqui que sea un check para que seleccione las palabras que quiera añadir a la lista
                            la id de los que vaya seleccionando se almacenaran en alguna variable para que al final cuando
                            le de en finalizar se guarden en la tabla list_has_words -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['submit_editar_lista'])) {

        

    }

    ?>
</body>

</html>