
var selectedItem;
var numPreguntas = 0;
var numPreguntasInARow = 0;
var cartasGiradas = 0;
var cartasGiradasInARow = 0;
var messageErrorDone = 0;
var sound = new Audio('sounds/sonic.mp3');


//Función para girar cartas
function rotate(card){
	//Parte de la función que girará las cartas del tablero
	if(card.className == 'flip-card'){
		card.classList.toggle('is-flipped');
		//document.getElementById("ventanaRecord").style.display = "inline";
		cartasGiradas++;
		cartasGiradasInARow++;
		messageErrorDone = 0;
		sound.play();
	}

	//Parte de la función que girará la carta asignada
	if(card.id == 'elegida'){
		card.parentNode.parentNode.classList.toggle('is-flipped');
	}
}

/*
* Esta función comprueba si el valor de la pregunta elegida coincide con el valor de la carta elegida
*/
function checkMatch(){
	var elegida = document.getElementById("elegida");
	if(elegida.getAttribute(selectedItem.id) == selectedItem.value){
		//alert("genial");
		win();
		document.querySelector("#countQuestions span").innerText = numPreguntas;
		return true;
	}else{
		//alert("No tan genial");
		return false;
	}
}


function win(){
	rotate(elegida);
	var modal = document.getElementById('myModal');
	modal.style.display = "block";    
}

function closeModal(div){
	/*var modal = document.getElementById('myModal');
	modal.style.display = "none";*/
	var mod = document.querySelector(".modal-content");
	mod.classList.toggle("collapsed");

}

function workCombo(form){
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
	form.reset();
}

/*
* Función para controlar cuántos combos han sido elegidos
* Si ha sido 1 devolverá true, si no ha sido ninguno o si ha elegido más de 1 devolverá false y un mensaje.
*/
function checkCombo(combos){
	var count = 0;

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

//Función que cierra la ventana emergente de error
function closeWindow(button){
	
	button.parentNode.style.display = "none";
}