<?php 
   session_start();
   
   include("../bd/conexion.php");
   if(!isset($_SESSION['id'])){
    header("Location: ./login.php");
    }
    $roomcode = $_GET['roomcode'];
?>

<!doctype html>
<html lang="es">
    
    <head>
    <title>Jugar en sala</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <!-- CSS propios -->
    <link rel="stylesheet" href="../assets/css/arenagame.css">
    <!-- Bootstrap JavaScript Libraries -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.js"></script>
</head>

<body>

    <header>
        <!-- place navbar here -->
    </header>
    <?php 
        $room = $roomcode;
        $id = $_SESSION['id'];
        $queryUser = $conexion->prepare("SELECT * FROM users WHERE id = ?");
        $queryUser->bind_param("i", $id);
        $queryUser->execute();
        $resultUser = $queryUser->get_result();

        if ($resultUser->num_rows > 0) {
            $rowUser = $resultUser->fetch_assoc();
        
            $name = $rowUser['name'];
            $lastname = $rowUser['lastname'];
            $email = $rowUser['email'];
            $school = $rowUser['school'];
            $avatar = $rowUser['idavatar'];
            $rol = $rowUser['roles_id'];
        }

        $queryUser->close();

        $queryRoom = $conexion->prepare("SELECT * FROM room WHERE roomcode = ?");
        $queryRoom->bind_param("s", $room);
        $queryRoom->execute();

        $resultRoom = $queryRoom->get_result();

        if ($resultRoom->num_rows > 0) {
            $rowRoom = $resultRoom->fetch_assoc();

            $roomname = $rowRoom['roomname'];
            $description = $rowRoom['description'];
            $lives = $rowRoom['lives'];
            $clue = $rowRoom['clue'];
            $clueafter = $rowRoom['clueafter'];
            $feedback = $rowRoom['feedback'];
            $random = $rowRoom['random'];
        } else {
            die('Error al conectar con la sala.');
        }

        $queryRoom->close();

    ?>
    <main>
        <div id="loading" class="row justify-content-center align-items-center g-2" style="height: 50vh;">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="modalNombre" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Sala</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <form action="javascript:void(0);" method="post" onsubmit="hangmanApp.aJugar();">
                                <label></label>
                                    <input type="hidden" name="username" value="<?php echo $name ?>">
                                    <input type="hidden" name="id_user" value="<?php echo $id ?>">
                                    <input type="hidden" name="id_room" value="<?php echo $room ?>">
                                <label>Nombre</label>
                                <label><?php echo $roomname ?></label>
                                <label>Descripcion:</label>
                                <label><?php echo $description ?></label>
                                <label>Lives:</label>
                                <label><?php echo $lives ?></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="window.location='index.html'">Cancelar</button>
                        <button type="submit" class="btn btn-primary">¡Jugar!</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Body -->
        <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
        <div class="modal fade" id="modalResult" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId2">Resultado</h5>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-primary">
                                <thead>
                                    <tr>
                                        <th scope="col">Dato</th>
                                        <th scope="col">Valor</th>
                                    </tr>
                                </thead>
                                <tbody id="resultTabla">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                            onclick="hangmanApp.valida();">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container oculto" id="divPrincipal">
            <!-- <div class="row justify-content-center align-items-center"> -->
            <div class="row align-items-center">
                <!-- <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4 my-3"> -->
                <div id="vidas" class="col">
                    Vidas: <span class="spanVidas"></span>
                </div>
                <!-- <div class="col">
                    Intentos: <span class="spanIntentos"></span>
                </div> -->
                <div id="puntos" class="col">
                    Puntos: <span class="spanPuntos"></span>
                </div>
                <div id="rendirse" class="col">
                    <input name="btnRendirse" id="btnRendirse" class="btn btn-primary" type="button" value="Rendirse"
                        onclick="hangmanApp.rendir();">
                </div>
            </div>
            <div id="verbo"class="row">
                <div class="col-12 text-center">Verbo: 
                    <span class="spanVerbo">
                    </span>
                </div>
            </div>
            <div id="pista" class="row">
                <div class="col-12 text-center">
                    <span class="spanPista">
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-12 justify-content-center" id="divHangman">
                    <img id="img1" src="../assets/img/hangman/baseH.png" width="220" height="200">
                    <img id="img2" src="../assets/img/hangman/head.png" width="220" height="200" class="oculto">
                    <img id="img3" src="../assets/img/hangman/body.png" width="220" height="200" class="oculto">
                    <img id="img4" src="../assets/img/hangman/ArmL.png" width="220" height="200" class="oculto">
                    <img id="img5" src="../assets/img/hangman/ArmR.png" width="220" height="200" class="oculto">
                    <img id="img6" src="../assets/img/hangman/LegL.png" width="220" height="200" class="oculto">
                    <img id="img7" src="../assets/img/hangman/LegR.png" width="220" height="200" class="oculto">
                </div>
            </div>
            <div class="row">
                <div id="divBotones" class="col-12 text-center">
                </div>
            </div>
            <div class="row oculto">
                <div id="divAdivinaPasadoTipo" class="col-12">
                    <form action="javascript:void(0);" method="post" class="form" 
                        onsubmit="hangmanApp.revisarPasado();" id="formulario">
                        <label class="form-label">Selecciona el tipo de verbo: </label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="tipoV" id="regular" value="r" checked>
                            <label class="form-check-label" for="regular">
                                Regular
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="tipoV" id="irregular" value="i">
                            <label class="form-check-label" for="irregular">
                                Irregular
                            </label>
                        </div>
                        <div class="mb-2">

                            <input required type="text" class="form-control" name="txtPasado" id="txtPasado" autocomplete="off"
                                aria-describedby="helpId" placeholder="Simple past">
                            <small id="helpId" class="form-text text-muted"></small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="btnSubmit" id="btnSubmit"
                                class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- <div id="loading" class="row justify-content-center align-items-center g-2" style="height: 50vh;">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div> -->
    </main>

    <footer>
        <!-- place footer here -->
    </footer>

    <script>
        const modalNombre = new bootstrap.Modal(document.getElementById('modalNombre'));
        const modalResult = new bootstrap.Modal(document.getElementById("modalResult"));
        var hangmanApp = new function () {
            this.spanVidas = document.querySelector('.spanVidas');
            //! this.spanIntentos = document.querySelector('.spanIntentos');
            this.spanPuntos = document.querySelector('.spanPuntos');
            this.spanPuntos.innerHTML = "0";
            this.spanVerbo = document.querySelector('.spanVerbo');
            this.spanPista = document.querySelector('.spanPista');
            this.divHangman = document.getElementById('divHangman');
            this.divBotones = document.getElementById('divBotones');
            this.divAdivinaPasadoTipo = document.getElementById('divAdivinaPasadoTipo');
            this.resultTabla = document.getElementById('resultTabla');
            this.nombre = document.getElementById('txtNombre').value;
            this.verboJuega = null;
            this.pasadoUsr = document.getElementById('txtPasado');
            this.tipoUsr = document.getElementsByName("tipoV");
            this.vidas = 3;
            this.pistaDespuesDe = 3;
            this.perdioLasVidas = false;
            this.seRindio = false;
            this.errores = 0;
            this.aciertos = 0;
            this.intentos = 6;
            this.verbos = new Array();
            this.verboDashes = new Array();
            this.verboArray = new Array();
            this.datosjuego = new Array();
            this.puntos = 0;
            this.urlApiWords = "./bd/apiwords.php";
            this.urlApiGeneral = "./bd/apigeneral.php";
            this.urlApiArena = "./bd/apiarena.php";
            this.spinner = document.getElementById("loading");

            this.randomKey = (max) => {
                return Math.floor(Math.random() * max);
            }

            this.leerVerbos = () => {
                fetch(this.urlApiWords + "?wrdLeer=1")
                    .then(respuesta => respuesta.json())
                    .then((respuesta) => {
                        respuesta.map(
                            function (palabra, index, array) {
                                hangmanApp.verbos.push(palabra);
                            }
                        );
                    })
                    .then(respuesta => {
                        this.iniciar();
                    })
                    .catch(console.log());
            };

            this.seleccionaVerbo = () => {
                let n = hangmanApp.verbos.length;
                let key = this.randomKey(n);
                verbo = hangmanApp.verbos[key];
                hangmanApp.verbos.splice(key, 1);
                return verbo;
            };

            this.iniciar = () => {
                document.getElementById("img7").style.visibility = "hidden";
                document.getElementById("img6").style.visibility = "hidden";
                document.getElementById("img5").style.visibility = "hidden";
                document.getElementById("img4").style.visibility = "hidden";
                document.getElementById("img3").style.visibility = "hidden";
                document.getElementById("img2").style.visibility = "hidden";
                hangmanApp.verboJuega = this.seleccionaVerbo();
                hangmanApp.errores = 0;
                hangmanApp.aciertos = 0;
                hangmanApp.intentos = 6;
                //hangmanApp.spanIntentos.innerHTML = hangmanApp.intentos;
                hangmanApp.spanVidas.innerHTML = "♥".repeat(hangmanApp.vidas);
                hangmanApp.verboArray = hangmanApp.verboJuega["word"].split("");
                hangmanApp.verboDashes = [].concat(hangmanApp.verboArray);
                hangmanApp.verboDashes.fill("_");
                hangmanApp.spanVerbo.innerHTML = "";
                hangmanApp.verboDashes.forEach(car => {
                    hangmanApp.spanVerbo.innerHTML += car + "   ";
                });
                hangmanApp.spanPista.classList.add("oculto");
                hangmanApp.spanPista.classList.add("quitar");
                hangmanApp.spanPista.innerHTML = verbo["clue"];
                this.crearBotones();
                hangmanApp.divBotones.classList.remove("quitar");
                hangmanApp.divAdivinaPasadoTipo.classList.remove("quitar");
                hangmanApp.divAdivinaPasadoTipo.classList.remove("visible");
                hangmanApp.divAdivinaPasadoTipo.classList.add("oculto");
                //document.getElementById("loading").style.display = "none";
                document.getElementById("loading").classList.add("quitar");
                document.getElementById("divPrincipal").classList.remove("oculto");
            };

            this.crearBotones = () => {
                let contenedor = document.getElementById("divBotones");
                contenedor.innerHTML = "";
                let alfabeto = new String("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
                let alfabetoArr = alfabeto.split('');
                alfabetoArr.forEach(letra => {
                    let boton = document.createElement("input");
                    boton.type = "button";
                    boton.id = letra;
                    boton.value = letra;
                    boton.className = "botonLetra";
                    //* Se programa el evento clic de cada botón
                    boton.onclick = function (e) {
                        if (hangmanApp.verboArray.includes(e.target.id)) {
                            let ind = 0;
                            hangmanApp.verboArray.forEach(car => {
                                if (car == e.target.id) {
                                    hangmanApp.verboDashes[ind] = car;
                                    hangmanApp.aciertos++;
                                    hangmanApp.puntos += 10;
                                    hangmanApp.spanPuntos.innerHTML = hangmanApp.puntos;
                                }
                                ind++;
                            });
                            hangmanApp.spanVerbo.innerHTML = "";
                            hangmanApp.verboDashes.forEach(car => {
                                hangmanApp.spanVerbo.innerHTML += car + "   ";
                            });
                            if (hangmanApp.aciertos == hangmanApp.verboArray.length) {
                                hangmanApp.adivinarPasado();
                            }
                        } else {
                            hangmanApp.intentos--;
                            hangmanApp.errores++;
                            hangmanApp.puntos -= 1;
                            hangmanApp.spanPuntos.innerHTML = hangmanApp.puntos;
                            //hangmanApp.spanIntentos.innerHTML = hangmanApp.intentos;
                            switch (hangmanApp.intentos) {
                                case 0:
                                    document.getElementById("img7").style.visibility = "visible";
                                    break;
                                case 1:
                                    document.getElementById("img6").style.visibility = "visible";
                                    break;
                                case 2:
                                    document.getElementById("img5").style.visibility = "visible";
                                    break;
                                case 3:
                                    document.getElementById("img4").style.visibility = "visible";
                                    break;
                                case 4:
                                    document.getElementById("img3").style.visibility = "visible";
                                    break;
                                case 5:
                                    document.getElementById("img2").style.visibility = "visible";
                                    break;
                            }
                            if (hangmanApp.errores == hangmanApp.pistaDespuesDe) {
                                hangmanApp.spanPista.classList.remove("quitar");
                                hangmanApp.spanPista.classList.remove("oculto");
                                hangmanApp.spanPista.classList.add("visible");
                            }
                            if (hangmanApp.errores == 6) {
                                hangmanApp.creaFeedback();
                                hangmanApp.vidas--;
                                hangmanApp.perdio();
                            }
                        }
                        let borraboton = document.getElementById(e.target.id);
                        divBotones.removeChild(borraboton);
                    };
                    contenedor.appendChild(boton);
                });
            }

            this.adivinarPasado = () => {
                
                var verbo = hangmanApp.verboJuega["word"];
                hangmanApp.divBotones.classList.add("quitar");
                document.getElementById("helpId").innerHTML = "Escribe el pasado simple del verbo " + verbo.toLowerCase();
                hangmanApp.divAdivinaPasadoTipo.classList.remove("oculto");
                hangmanApp.divAdivinaPasadoTipo.classList.add("visible");
            };

            this.revisarPasado = () => {
                let form=document.getElementById("formulario");
                hangmanApp.divAdivinaPasadoTipo.classList.add("quitar");
                tipoOk = hangmanApp.verboJuega["type"].toLowerCase().trim();
                pasadoOk = hangmanApp.verboJuega["simplepast"].toLowerCase().trim();
                pasadoTest = this.pasadoUsr.value.toLowerCase().trim();
                for (var i = 0; i < this.tipoUsr.length; i++) {
                    if (this.tipoUsr[i].checked) {
                        tipoTest = this.tipoUsr[i].value;
                        break;
                    }
                }
                hangmanApp.creaFeedback();

                if (tipoTest === tipoOk && pasadoTest === pasadoOk) {
                    hangmanApp.gano();
                    this.puntos += 15;
                } else {
                    this.vidas--;
                    this.puntos -= 10;
                    hangmanApp.perdio();
                }
                form.reset();
            };

            this.gano = () => {
                document.getElementById("modalTitleId2").innerHTML = "¡Correcto :) !";
                modalResult.show();
            };

            this.perdio = () => {
                
                document.getElementById("modalTitleId2").innerHTML = "¡Incorrecto :( !";
                modalResult.show();
            };

            this.valida = () => {
                if (this.vidas == 0) {
                    hangmanApp.spanVidas.innerHTML = "";
                    this.perdioLasVidas = true;
                    this.terminar();
                }
                else this.iniciar();
            };

            this.creaFeedback = () => {
                resultado = "<tr>" +
                    "<td>Verbo:</td>" +
                    "<td>" + this.verboJuega["word"].toLowerCase() + "</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td>Tipo:</td>" +
                    "<td>" + ((this.verboJuega["type"].toLowerCase() == "r") ? "regular" : "irregular") + "</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td>Pasado:</td>" +
                    "<td>" + this.verboJuega["simplepast"].toLowerCase() + "</td>" +
                    "</tr>" +
                    "<tr>" +
                    "<td>Ejemplo:</td>" +
                    "<td>" + this.verboJuega["example"] + "</td>" +
                    "</tr>";
                this.resultTabla.innerHTML = resultado;
            };

            this.insertarJuego = async () => {
                var datosEnviar = { nombre: hangmanApp.nombre };
                await fetch(this.urlApiArena + "?nuevo=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                    .then(respuesta => respuesta.json())
                    .then((respuesta) => {
                        this.datosjuego.push(respuesta);
                    })
                    .finally(respuesta => {
                    })
                    .catch(console.log);
            };

            this.terminar = async () => {
                //document.getElementById("loading").style.display = "block";
                document.getElementById("loading").classList.remove("quitar");
                document.getElementById("vidas").style.display = "none";
                document.getElementById("rendirse").style.display = "none";
                document.getElementById("verbo").style.display = "none";
                document.getElementById("pista").style.display = "none";
                document.getElementById("divHangman").style.display = "none";
                document.getElementById("puntos").style.display = "none";
                hangmanApp.divAdivinaPasadoTipo.remove("quitar");
                hangmanApp.spanPista.classList.remove("quitar");
                hangmanApp.spanVidas.classList.add("quitar");
                hangmanApp.divBotones.classList.add("quitar");
                idSend = hangmanApp.datosjuego[0][0]["id"];
                rindioSend = (hangmanApp.seRindio) ? 1 : 0;
                puntosSend = (hangmanApp.seRindio) ? 0 : this.puntos;

                var datosEnviar = { id: idSend, puntos: puntosSend, rindio: rindioSend };
                await fetch(this.urlApiArena + "?fin=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                    .then(respuesta => respuesta.json())
                    .then((respuesta) => {
                        window.location = "tablageneral.html";
                    })
                    .finally(respuesta => {
                    })
                    .catch(console.log);
            }

            this.aJugar = () => {
                this.nombre = document.getElementById("txtNombre").value;
                modalNombre.hide();
                hangmanApp.insertarJuego();
                hangmanApp.leerVerbos();
            };

            this.rendir = () => {
                this.seRindio = confirm("¿De verdad deseas rendirte?");
                if (this.seRindio) {
                    this.terminar();
                }
            };

        }
        modalNombre.show();
    </script>
</body>

</html>
