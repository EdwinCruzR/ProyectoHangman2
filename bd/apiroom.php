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
    $roomid = $data->roomid;
    $verbo = $data->verbAdivinado;
    $tipo = $data->tipo;
    $pasado = $data->pasado;
    $puntos = $data->puntos;
    
    $insert = mysqli_query($conexion, "INSERT INTO detailgameroom( gameroom_id, word_id, guessed, typecorrect, pastcorrect,pointsperword) VALUES ($gameroomid, $wordid, $verbo, $tipo, $pasado, $puntos)");
    
    $sql = mysqli_query($conexion, "SELECT * FROM room_has_word WHERE word_id= $wordid AND room_id= $roomid");
    while ($row = mysqli_fetch_array($sql)) {
        $usada = $row['used'];
        $adivinado = $row['guessed'];
        $tipoFail = $row['typefails'];
        $pasadoFail = $row['pastfails'];
    }
    
    $usada = $usada + 1;
    $adivinado = $adivinado + $verbo;
    $tipoFail = $tipoFail + (($tipo == 1)? 0 : 1);
    $pasadoFail = $pasadoFail + (($pasado == 1)? 0 : 1);
    
    $sql2 = mysqli_query($conexion, "UPDATE room_has_word SET used = $usada, guessed=$adivinado, typefails= $tipoFail, pastfails=$pasadoFail WHERE word_id= $wordid AND room_id= $roomid");
    
    echo json_encode([["success" => 0]]);
    exit();
}


if (isset($_GET["fin"])) {
    $data = json_decode(file_get_contents("php://input"));
    $iduser = $data->userid;
    $idgr = $data->idgr;
    $puntos = $data->puntos;
    $rindio = $data->rindio;

    mysqli_query($conexion, "UPDATE gameroom SET score = $puntos , timestampend = CURRENT_TIMESTAMP, totaltime = TIMEDIFF( timestampend , timestampstart) WHERE id = $idgr");
    // $consultTime = mysqli_query($conexion, "SELECT totaltime FROM gameroom WHERE id= $idgr");
    // while ($rows = mysqli_fetch_array($consultTime)) {
    //     $hrsjged = $rows['totaltime'];
    // }

    // mysqli_query($conexion, "UPDATE users SET hrsjugadas = hrsjugadas + $hrsjged WHERE id = $iduser");
    
    echo json_encode([["success" => 1]]);
    exit();
}

if (isset($_GET["tablaSala"])) {
    $data = json_decode(file_get_contents("php://input"));
    $roomid = $data->idrm;
    $sql = mysqli_query($conexion, "SELECT gameroom.*, users.name FROM gameroom JOIN users ON gameroom.user_id = users.id WHERE gameroom.room_id = $roomid ORDER BY gameroom.score DESC");
    if (mysqli_num_rows($sql) > 0) {
        $tabla = mysqli_fetch_all($sql, MYSQLI_ASSOC);
        echo json_encode($tabla);
    } else {
        echo json_encode([["success" => 0]]);
    }
}

?>