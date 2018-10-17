
/*var cartas = [];
var selectedItem;

function Carta(imagen,gafas,pelo,genero){
	this.imagen = imagen;
	this.gafas = gafas;
	this.pelo = pelo;
	this.genero = genero;
}


Carta.prototype.imagen = function() {
    return this.imagen;
};
Carta.prototype.gafas = function() {
    return this.gafas;
};
Carta.prototype.pelo = function() {
    return this.pelo;
};
Carta.prototype.genero = function() {
    return this.genero;
};*/

function workCombo(){
	var combos = document.getElementsByClassName("combo");
	if(checkCombo(combos)){
		selectedItem.disabled = true;
		document.getElementById("texto").innerHTML = "Todo ok.";
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
			document.getElementById("preguntas").reset();
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