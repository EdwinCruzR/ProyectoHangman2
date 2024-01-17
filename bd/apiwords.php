<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include("conexion.php");

//* Lectura de todos los verbos
if (isset($_GET["wrdLeer"])) {
    $query = "SELECT * FROM words WHERE user_id = 1 ORDER by word ASC";
    $sql = mysqli_query($conexion, $query);
    if (mysqli_num_rows($sql) > 0) {
        $listaVerbos = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        echo json_encode($listaVerbos);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

if (isset($_GET["wrdRoomLeer"])) {
    $data = json_decode(file_get_contents("php://input"));
    $roomid = $data->roomid;
    $query = "SELECT words.* FROM words JOIN room_has_word ON words.id = room_has_word.word_id WHERE room_has_word.room_id = $roomid";
    $sql = mysqli_query($conexion, $query);
    if (mysqli_num_rows($sql) > 0) {
        $listaVerbos = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        echo json_encode($listaVerbos);
    } else {
        echo json_encode([["success" => 0]]);
    }
}
?>