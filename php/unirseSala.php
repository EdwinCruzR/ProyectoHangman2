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
    <title>Unirse sala</title>
</head>
<body>
    
    <?php   
    $id = $_SESSION['id'];
    $query = mysqli_query($conexion,"SELECT * FROM users WHERE id=$id");

    while($result = mysqli_fetch_assoc($query)){
        $name = $result['name'];
        $lastname = $result['lastname'];
        $email = $result['email'];
        $school = $result['school'];
        $avatar = $result['idavatar'];
        $rol = $result['roles_id'];
    }

        if(isset($_POST['unirse'])){

            $roomcode = $_POST['roomcode'];
            
            $verifCode = mysqli_query($conexion, "SELECT roomcode FROM room WHERE roomcode='$roomcode'");

            if(mysqli_num_rows($verifCode) !=0 ){
                $isopen = mysqli_query($conexion, "SELECT isopen FROM room WHERE isopen = 1 AND roomcode='$roomcode'");
                if(mysqli_num_rows($isopen) !=0 ){
                    // si esta abierta la sala entra a jugar
                    ?>
                    <a href="./roomgame.php?r=<?php echo $roomcode?>"><button class='btn'>Jugar</button>
                    <?php
                }else{
                    // no esta abaierta la sala
                    ?>
                    <div class='message'>
                        <p>La sala esta cerrada</p>
                    </div> <br>
                    <a href='unirseSala.php'><button class='btn'>Volver</button>
                <?php
                }
            }else{
                ?>
                    <div class='message'>
                        <p>No encontro la sala</p>
                    </div> <br>
                    <a href='unirseSala.php'><button class='btn'>Volver</button>
                <?php
            }
        }   
        ?>


    <h1>Unirse Sala</h1>
    <h2>Unete a una sala<?php echo $name ?></h2>
        <form action="" method="POST">
            <input type="text" name="roomcode" placeholder="ej. xyz7yz">
            <input type="submit" value="unirse" name="unirse">
        </form>
</body>
</html>