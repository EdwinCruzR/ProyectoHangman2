<?php 
   session_start();
   include("../bd/conexion.php");
   if(!isset($_SESSION['id'])){
    header("Location: ./login.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/unirseSala.css">
    
    <link rel="stylesheet" href="../assets/css/salas.css">
    <title>Unirse sala</title>
</head>
<body>

    
    <?php   
    $id = $_SESSION['id'];
    $query = mysqli_query($conexion,"SELECT * FROM users WHERE id=$id");

    while($result = mysqli_fetch_assoc($query)){
        $name = $result['name'];
    }

        if(isset($_POST['unirse'])){

            $roomcode = $_POST['roomcode'];
            
            $verifCode = mysqli_query($conexion, "SELECT id FROM room WHERE roomcode='$roomcode'");
            
            if(mysqli_num_rows($verifCode) !=0 ){
                
                while($result2 = mysqli_fetch_assoc($verifCode)){
                    $roomid = $result2['id'];
                }
                $existwords = mysqli_query($conexion, "SELECT word_id FROM room_has_word WHERE room_id = $roomid");
                $isopen = mysqli_query($conexion, "SELECT isopen FROM room WHERE isopen = 1 AND roomcode='$roomcode'");
                if(mysqli_num_rows($isopen) !=0 ){
                    if (mysqli_num_rows($existwords) != 0) {
                        header("Location: ./roomgame.php?roomcode=" . $roomcode);
                        exit();
                        
                    } else {
                        $message = "La sala no tiene palabras seleccionadas";
                        // mysqli_stmt_close($existwords);
                    }
                } else {
                    // La sala está cerrada
                    $message = "La sala está cerrada";
                }
            } else {
                // No se encontró la sala
                $message = "No se encontró la sala";
            }
        } 
        
        ?>

    <h2>Unete a una sala <?php echo $name ?></h2>

    <?php if(isset($message)){ ?>
        <div class='message'>
            <p><?php echo $message; ?></p>
        </div>
        <br>
    <?php } ?>

    <form action="" method="POST">
        <label for="sala">Codigo de Sala:</label>
        <input type="text" name="roomcode" placeholder="ej. xyz7yz" required>
        <button type="submit" name="unirse">Unirse a la Sala</button>
        <a href="./dashpage.php"><button type="button" class="btn btn-danger regresar">Regresar</button></a>
    </form>
</body>
</html>