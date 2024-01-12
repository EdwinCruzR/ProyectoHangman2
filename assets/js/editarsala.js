var checkbox = document.getElementById('unlimitedLives');
      var numLivesInput = document.getElementById('numLives');

        if (checkbox.checked) {
            numLivesInput.disabled = true;
        } else {
            numLivesInput.disabled = false;
        }

    function toggleLivesInput() {
      var checkbox = document.getElementById('unlimitedLives');
      var numLivesInput = document.getElementById('numLives');

        if (checkbox.checked) {
            numLivesInput.disabled = true;
        } else {
            numLivesInput.disabled = false;
        }
    }

    function toggleCluesInput(){
      var cluesInputs = document.getElementById("errorNumber");
      if(document.getElementById("showHints").checked) {
        cluesInputs.disabled=false;
      } else {
        cluesInputs.disabled=true;
      }
    }

    function toggleRoomStatus() {
      var statusSource = document.getElementById("statusSource");
      var divClose = document.getElementById("settimeclose");
      var divOpen = document.getElementById("settimeopen");
      
      if (divClose && divOpen) {
        switch (statusSource.value) {
          case "setTime":
            divClose.style.display = "block";
            divOpen.style.display = "block";
            break;
          case "hasstartdatetime":
            divOpen.style.display = "block";
            divClose.style.display = "none";
            break;
          case "hasenddatetime":
            divClose.style.display = "block";
            divOpen.style.display = "none";
            break;
        }
      }
    }

    function toggleWordList() {
      var wordList = document.getElementById("wordList");
      var wordSource = document.getElementById("wordSource");
      
      // Muestra la lista de palabras solo cuando se selecciona "Palabras del docente"
      wordList.style.display = (wordSource.value === "teacher") ? "block" : "none";
    }