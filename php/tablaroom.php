<?php 
   session_start();
   
   include("../bd/conexion.php");
   if(!isset($_SESSION['id'])){
    header("Location: ./login.php");
    }
    $roomid = $_GET['r'];
    $userid = $_SESSION['id'];
?>
<!doctype html>
<html lang="es">
<head>
    <title>Hangman: Tabla de posiciones de la sala</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
        </script><link rel="stylesheet" href="../assets/css/arenagame.css">
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>
        <div id="loading" class="row justify-content-center align-items-center g-2" style="height: 50vh;">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        
        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="container-sm">
            <div style="height: 90vh" class="modal modal-dialog modal-dialog-scrollable" id="modalId" tabindex="-1"
                data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTitleId">Tabla de posiciones</h5>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table style="font-size: 0.7rem;"
                                    class="table table-striped table-hover table-borderless table-success align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Avatar</th>
                                            <th>Nombre</th>
                                            <th>Puntos</th>
                                            <th>Tiempo</th>
                                            <th>Observ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="players" class="table-group-divider">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                                onclick="window.location='dashpage.php'">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <!-- place footer here -->
    </footer>

    <script>
        const myModal = new bootstrap.Modal(document.getElementById('modalId'))
        var tablaGeneral = new function () {
            this.urlApiRoom = "../bd/apiroom.php";
            this.tabla = document.getElementById("players");
            this.leer = async () => {
                var datosEnviar = { idrm: <?= $roomid ?> };  
                await fetch(this.urlApiRoom + "?tablaSala=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                    .then(respuesta => respuesta.json())
                    .then((respuesta) => {
                        document.getElementById("loading").classList.add("quitar");
                        myModal.show();
                        respuesta.map(
                            function (registro, index, array) {
                                tablaGeneral.tabla.innerHTML += "<tr><td>" + (index + 1) + "</td>" +
                                    "<td>" + ("<img src=../assets/img/avatar/0.png width=20></img>") + "</td>" +
                                    "<td>" + (registro.name) + "</td>" +
                                    "<td>" + (registro.score) + "</td>" +
                                    "<td>" + (registro.totaltime) + "</td>" +
                                    "<td>" + ((registro.status == 1) ? "Se rindió" :(registro.status == -1) ? "Vidas Ilimitadas" : (registro.status == 2)?"Acabó verbos" :"Acabó vidas" ) + "</td>" +
                                    "</tr>";
                            }
                        );
                    })
                    .then(respuesta => {
                    })
                    .catch(console.log());
            };
        }

        tablaGeneral.leer();
        
    </script>
</body>

</html>