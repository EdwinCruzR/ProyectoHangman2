<?php 
   session_start();
   
   include("../bd/conexion.php");
   if(!isset($_SESSION['id'])){
    header("Location: ./login.php");
    }
    $roomcode = $_GET['roomcode'];
    $id = $_SESSION['id'];
?>
<!doctype html>
<html lang="es">

<head>
    <title>Jugar en Sala</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="../assets/bootstrap/themes/sketchy/bootstrap.css" rel="stylesheet">
    <!-- CSS propios -->
    <link rel="stylesheet" href="../assets/css/arenagame.css">
    <!-- Bootstrap JavaScript Libraries -->
    <script src="../assets/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
        leerReglas = async () => {
            var datosEnviar = { roomcode: '<?= $roomcode ?>' };
            await fetch("../bd/apiroom.php?rulesLeer=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                .then(respuesta => respuesta.json())
                .then((respuesta) => {
                    // console.log(respuesta);
                    const datoname = respuesta[0].roomname;
                    const datodescription = respuesta[0].description;
                    const datolives = respuesta[0].lives;
                    const datoclueafter = respuesta[0].clueafter;
                    let name = document.getElementById('roomname');
                    name.innerHTML ="Nombre de la sala: " + datoname;
                    let descriptiom = document.getElementById('description');
                    descriptiom.innerHTML ="Descripcion: " + datodescription;
                    let vidas = document.getElementById('vidas');
                    vidas.innerHTML ="Vidas: " + (datolives == 0 ? "Ilimitadas" : datolives);
                    let clueafter = document.getElementById('clueafter');
                    clueafter.innerHTML ="Pistas despues de: " + datoclueafter + " intentos";
                    
                })
                .finally(respuesta => {
                })
                .catch(console.log());
        };
    </script>
</head>

<body onload="leerReglas();">

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
        <div class="modal fade" id="modalNombre" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">Datos Sala</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <form action="javascript:void(0);" method="post" onsubmit="hangmanApp.leerReglas();">
                                <div class="vidas">
                                    <p id="roomname"></p>
                                    <p id="description"></p>
                                    <p id="vidas"></p>
                                    <p id="clueafter"></p>
                                </div>    
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="window.location='dashpage.php'">Cancelar</button>
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
            //this.nombre = document.getElementById('txtNombre').value;
            this.verboJuega = null;
            this.pasadoUsr = document.getElementById('txtPasado');
            this.tipoUsr = document.getElementsByName("tipoV");
            this.vidas = 0;
            this.pistaDespuesDe = 0;
            this.isfeedback = true;
            this.randomWords = false;
            this.systemWords = true;
            this.idroom = 0;
            this.idgameroom = 0;
            this.wassguess = 0;
            this.wastype = 0;
            this.waspast = 0;
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
            this.urlApiWords = "../bd/apiwords.php";
            this.urlApiGeneral = "../bd/apigeneral.php";
            this.urlApiRoom = "../bd/apiroom.php";
            this.spinner = document.getElementById("loading");

            this.randomKey = (max) => {
                return Math.floor(Math.random() * max);
            }
            

            this.leerVerbos = async () => {
                var datosEnviar = { roomid: hangmanApp.idroom };
                await fetch(this.urlApiWords + "?wrdRoomLeer=1", { method: "POST", body: JSON.stringify(datosEnviar) })
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

            this.leerReglas = async () => {
                var datosEnviar = { roomcode: '<?= $roomcode ?>' };
                await fetch(this.urlApiRoom + "?rulesLeer=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                    .then(respuesta => respuesta.json())
                    .then((respuesta) => {
                        console.log(respuesta[0].id);
                        hangmanApp.idroom = respuesta[0].id;
                        hangmanApp.vidas = respuesta[0].lives;
                        hangmanApp.pistaDespuesDe = respuesta[0].clueafter;
                        hangmanApp.isfeedback = (respuesta[0].feedback == 1 ? true : false);
                        hangmanApp.randomWords = (respuesta[0].random == 1 ? true : false);
                        hangmanApp.systemWords = (respuesta[0].isgeneral == 1 ? true : false);
                    })
                    .finally(respuesta => {
                    })
                    .catch(console.log());
                    hangmanApp.aJugar();
            };

            this.seleccionaVerbo = () => {
                if (hangmanApp.verbos.length == 0) {
                    hangmanApp.terminar();
                }else{
                    if(hangmanApp.randomWords){
                        let n = hangmanApp.verbos.length;
                        let key = this.randomKey(n);
                        verbo = hangmanApp.verbos[key];
                        hangmanApp.verbos.splice(key, 1);
                        return verbo;
                    }else{
                        verbo = hangmanApp.verbos[0];
                        hangmanApp.verbos.splice(0, 1);
                        return verbo;
                    }
                }
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
                hangmanApp.spanVidas.innerHTML = (hangmanApp.vidas==-1)? "Ilimitadas" : "♥".repeat(hangmanApp.vidas);
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
                                hangmanApp.wassguess = 0;
                                hangmanApp.wastype = 0;
                                hangmanApp.waspast = 0;
                                hangmanApp.datailgameroom();
                                hangmanApp.creaFeedback();
                                //hangmanApp.vidas--;
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
                hangmanApp.wastype = tipoTest == tipoOk ? 1 : 0;
                hangmanApp.waspast = pasadoTest == pasadoOk ? 1 : 0;
                hangmanApp.wassguess = 1;
                hangmanApp.datailgameroom();


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
                if(!(hangmanApp.isfeedback)){
                    resultado = "";
                } else{
                resultado = 
                "<thead>"+
                    "<tr>" +
                    "<th scope='col'>Dato</th>"+
                    "<th scope='col'>Valor</th>"+
                    "</tr>"+
                    "</thead>"+
                    "<tr>" +
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
                }
                this.resultTabla.innerHTML = resultado;
            };

            this.datailgameroom = async () => {
                var datosEnviar = {gameroomid: hangmanApp.idgameroom ,wordid: hangmanApp.verboJuega["id"] ,verbAdivinado: hangmanApp.wassguess,  tipo : hangmanApp.wastype, pasado : hangmanApp.waspast };
                await fetch(this.urlApiRoom + "?detail=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                    .then(respuesta => respuesta.json())
                    .finally(respuesta => {
                    })
                    .catch(console.log);
            };

            this.insertarJuego = async () => {
                var datosEnviar = { userid : <?= $id ?>, roomid : hangmanApp.idroom };
                await fetch(this.urlApiRoom + "?nuevo=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                    .then(respuesta => respuesta.json())
                    .then((respuesta) => {
                        hangmanApp.idgameroom = respuesta[0].id;
                        console.log(respuesta[0].id);
                        hangmanApp.idgameroom = respuesta[0].id;
                        // this.datosjuego.push(respuesta);
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

                // idSend = hangmanApp.datosjuego[0][0]["id"];
                rindioSend = (hangmanApp.seRindio) ? 1 : 0;
                puntosSend = (hangmanApp.seRindio) ? 0 : this.puntos;
                console.log('id game room '+hangmanApp.idgameroom);
                console.log(puntosSend);
                console.log(rindioSend);

                var datosEnviar = { idgr: hangmanApp.idgameroom, puntos: puntosSend, rindio: rindioSend };  
                await fetch(this.urlApiRoom + "?fin=1", { method: "POST", body: JSON.stringify(datosEnviar) })
                    .then(respuesta => respuesta.json())
                    .then((respuesta) => {
                        console.log('hubo respuesta');
                        // window.location = "tablageneral.html";
                    })
                    .finally(respuesta => {
                    })
                    .catch(console.log);
            }

            this.aJugar = () => {
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
