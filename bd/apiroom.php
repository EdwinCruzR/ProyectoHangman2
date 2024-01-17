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

    "INSERT INTO gameroom (user_id, room_id) VALUES ()"
    $sql = mysqli_query($conexion, "INSERT INTO roomgame(player) VALUES('$nombre')");


    $sql = mysqli_query($conexion, "INSERT INTO roomgame(player) VALUES('$nombre')");
    $sql2 = mysqli_query($conexion, "SELECT * FROM arenagame WHERE id = (SELECT MAX(id) FROM arenagame)");
    $juego = mysqli_fetch_all($sql2, MYSQLI_ASSOC);
    echo json_encode($juego);
    exit();
}




if (isset($_GET["fin"])) {
    $data = json_decode(file_get_contents("php://input"));
    $id = (int) $data->id;
    $puntos = (int) $data->puntos;
    $rindio = (int) $data->rindio;
    $sql2 = mysqli_query($conexion, "UPDATE arenagame SET score = '$puntos', 
                                                          giveup = b'$rindio',
                                                          timestampend = default,
                                                          totaltime =  timediff(timestampend,timestampstart)
                                                          WHERE id = '$id'");
    echo json_encode(["success" => 1]);
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