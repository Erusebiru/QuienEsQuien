
var selectedItem;
var numPreguntas = 0;
var numPreguntasInARow = 0;
var cartasGiradas = 0;
var cartasGiradasInARow = 0;
var messageErrorDone = 0;
var sound = new Audio('sounds/sonic.mp3');

function rotate(card){
	if(card.className == 'flip-card'){
		card.classList.toggle('is-flipped');
		//document.getElementById("ventanaRecord").style.display = "inline";
		cartasGiradas++;
		cartasGiradasInARow++;
		messageErrorDone = 0;
		sound.play();
	}
	if(card.id == 'elegida'){
		card.parentNode.parentNode.classList.toggle('is-flipped');
	}
}

function checkMatch(){
	var elegida = document.getElementById("elegida");
	if(elegida.getAttribute(selectedItem.id) == selectedItem.value){
		alert("genial");
		rotate(elegida);
		return true;
	}else{
		alert("No tan genial");
		return false;
	}
}

function workCombo(){
	var combos = document.getElementsByClassName("combo");
	if(cartasGiradasInARow == 0 && messageErrorDone == 1){
		alert("No has girado ninguna carta");
		messageErrorDone++;
	}else{
		if(checkCombo(combos)){
			//Todo ok
			numPreguntas++;
			checkMatch();
			messageErrorDone++;
			if(cartasGiradasInARow > 0){
				cartasGiradasInARow = 0;
			}
		}
	}
	
	document.getElementById("preguntas").reset();
}

function checkCombo(combos){
	var count = 0;
	var comboDisabled = 0;
	for(var e=0;e<combos.length;e++){
		if(combos[e].disabled == true){
			comboDisabled++;
		}
	}
	if(comboDisabled == combos.length-1){
		document.getElementById("preguntar").disabled = true;
	}
	for(var i=0;i<combos.length;i++){
		if(combos[i].selectedIndex){
			selectedItem = combos[i];
			count++;
		}
		if(count > 1){
			document.getElementById("ventanaError").style.display = "inline"
			document.getElementById("textError").innerHTML = "Debes realizar sólo una pregunta.";
			return false;
		}
	}
	if(count == 0){
		document.getElementById("textError").innerHTML = "No has seleccionado ninguna pregunta.";
		document.getElementById("ventanaError").style.display = "inline"
		return false
	}else{
		return true;
	}
	
}

function closeWindow(button){
	
	button.parentNode.style.display = "none";
}