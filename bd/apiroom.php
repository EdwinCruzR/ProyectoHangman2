<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include("conexion.php");



//* Lectura de todos los verbos
if (isset($_GET["rulesLeer"])) {
    $data = json_decode(file_get_contents("php://input"));
    $roomcode = $data->roomcode;

    // $query = "SELECT * FROM room WHERE roomcode = '$roomcode'";
    $sql = mysqli_query($conexion, "SELECT * FROM room WHERE roomcode = '$roomcode'");
    if (mysqli_num_rows($sql) > 0) {
        $rules = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        echo json_encode($rules);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

if (isset($_GET["nuevo"])) {
    $data = json_decode(file_get_contents("php://input"));
    $userid = $data->userid;
    $roomid = $data->roomid;

    $sql = mysqli_query($conexion, "INSERT INTO gameroom (user_id, room_id) VALUES ($userid, $roomid)");
    $sql2 = mysqli_query($conexion, "SELECT * FROM gameroom WHERE room_id= $roomid AND user_id = $userid ORDER BY id DESC");
    if (mysqli_num_rows($sql2) > 0) {
        $juego = mysqli_fetch_all($sql2, MYSQLI_ASSOC);
    echo json_encode($juego);
    } else {
        echo json_encode([["success" => 0]]);
    }
    exit();
}

if (isset($_GET["detail"])) {
    $data = json_decode(file_get_contents("php://input"));
    $gameroomid = $data->gameroomid;
    $wordid = $data->wordid;
    $verbo = $data->verbAdivinado;
    $tipo = $data->tipo;
    $pasado = $data->pasado;
    $sql = mysqli_query($conexion, "INSERT INTO detailgameroom( gameroom_id, word_id, guessed, typecorrect, pastcorrect) VALUES ($gameroomid, $wordid, $verbo, $tipo, $pasado)");
    
    echo json_encode([["success" => 0]]);
    exit();
}

if (isset($_GET["fin"])) {
    $data = json_decode(file_get_contents("php://input"));
    $idgr = $data->idgr;
    $puntos = $data->puntos;
    $rindio = $data->rindio;
    echo $idgr;
    echo $puntos;
    echo $rindio;
    mysqli_query($conexion, "UPDATE gameroom SET score = $puntos , timestampend = CURRENT_TIMESTAMP, totaltime = TIMEDIFF( timestampend , timestampstart) WHERE id = $idgr");
    // mysqli_query($conexion, "UPDATE gameroom SET score = $puntos , timestampend = CURRENT_TIMESTAMP, totaltime = TIMEDIFF( timestampend , timestampstart) WHERE id = $idgr");
    // $sql2 = mysqli_query($conexion, "UPDATE gameroom SET totaltime = TIMEDIFF( timestampend , timestampstart) WHERE id = $idgr");
    echo json_encode([["success" => 1]]);
    exit();
}




if (isset($_GET["tablaGeneral"])) {
    $sql = mysqli_query($conexion, "SELECT * FROM arenagame ORDER by score DESC, totaltime ASC");
    if (mysqli_num_rows($sql) > 0) {
        $tabla = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        echo json_encode($tabla);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

?>