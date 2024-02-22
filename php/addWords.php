<?php
session_start();

include("../bd/conexion.php");
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
}

$iduser = $_SESSION['id'];
$idLista = $_GET['id'];

// Consultar las palabras asociadas a la lista actual
$consultaWordsOnList = mysqli_query($conexion, "SELECT word_id FROM list_has_word WHERE list_id = $idLista");

// Crear un array para almacenar las ID de las palabras que ya estan en la lista del usuario
$WordsOnList = array();
while ($row = mysqli_fetch_array($consultaWordsOnList)) {
    $WordsOnList[] = $row['word_id'];
}

if (isset($_POST['submit_editar_lista'])) {
    $selectedWords = isset($_POST['selected_words']) ? $_POST['selected_words'] : array();

    $selectROP = mysqli_query($conexion, "SELECT id FROM room WHERE user_id = $iduser");

    $roomsofplayer = array();
    while ($row = mysqli_fetch_array($selectROP)) {
        $roomsofplayer[] = $row['id'];
    }

    //palabras que fueron deseleccionadas
    $deselectedWords = array_diff($WordsOnList, $selectedWords);

    //palabras que fueron seleccionadas
    $selectedWords = array_diff($selectedWords, $WordsOnList);

    foreach ($selectedWords as $idPalabra) {
        $insertListWord = mysqli_query($conexion, "INSERT INTO list_has_word (list_id, word_id) VALUES ($idLista, $idPalabra)");
        foreach ($roomsofplayer as $idRoomes) {
            $insertRoomWord = mysqli_query($conexion, "INSERT INTO room_has_word (room_id, word_id) VALUES ($idRoomes, $idPalabra)");
        }
    }

    foreach ($deselectedWords as $idPalabra) {
        $deleteListWord = mysqli_query($conexion, "DELETE FROM list_has_word WHERE list_id = $idLista AND word_id = $idPalabra");
        foreach ($roomsofplayer as $idRoomes) {
            $deleteRoomWord = mysqli_query($conexion, "DELETE FROM room_has_word WHERE word_id = $idPalabra AND room_id = $idRoomes");
        }
    }

    echo "<script> alert('lista actualizada con exito'); </script>";
    header("Location: ./dashpage.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/editar.css">
    <link rel="stylesheet" href="../assets/css/addWords.css">
    <title>Añadir Palabras</title>
</head>

<body>
<a href="./dashpage.php"><button type="button" class="btn btn-danger regresar">Regresar</button></a>
    <div class="listas">
        <div id="listas_crear" class="content">
            <div class="form-container">
                <h1>Añadir palabras Lista</h1>
                <form id="addWordForm" method="post">
                    <?php
                    $consulta_allWords = mysqli_query($conexion, "SELECT * FROM words WHERE user_id = $iduser AND isactive = 1");
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Palabra</th>
                                <th>Español</th>
                                <th>Tipo</th>
                                <th>Pasado simple</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($consulta_allWords)) {
                                $idWord = $row['id'];
                                $word = $row['word'];
                                $type = ($row['type'] == "I") ? "Irregular" : "Regular";
                                // Verificar si la palabra está asociada y marcar el checkbox
                                $checked = (in_array($idWord, $WordsOnList)) ? 'checked' : '';
                            ?>
                                <tr>
                                    <th><?= $row['id'] ?></th>
                                    <th><?= $row['word'] ?></th>
                                    <th><?= $row['spanish'] ?></th>
                                    <th><?= (($row['type'] == "I")? "Irregular" : "Regular") ?></th>
                                    <th><?= $row['simplepast'] ?></th>
                                    <td>
                                        <input class="checkbox" type="checkbox" id="word_<?= $idWord ?>" name="selected_words[]" value="<?= $idWord ?>" <?= $checked ?> >
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <input type="submit" class="form-button" name="submit_editar_lista" value="Actualizar lista">
                </form>
            </div>
        </div>
    </div>
</body>

</html>