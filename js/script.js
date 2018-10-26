
var selectedItem;
var numPreguntas = 0;
var numPreguntasInARow = 0;
var cartasGiradas = 0;
var cartasGiradasInARow = 0;
var messageErrorDone = 0;
var giro = new Audio('sounds/sonic.mp3');
var perder = new Audio('sounds/pacman-dies.mp3');
var ganar = new Audio('sounds/super-mario-castle-bros.mp3');
var bloqueo = 0;

var canvas, width, height, ctx;
var fireworks = [];
var particles = [];


//Función para girar cartas
function rotate(card){
	//Parte de la función que girará las cartas del tablero
	if(card.className == 'flip-card'){
		card.classList.toggle('is-flipped');
		card.setAttribute('name','girada');
		//document.getElementById("ventanaRecord").style.display = "inline";
		cartasGiradas++;
		console.log(cartasGiradas);
		if(cartasGiradas==11){
			endOfGame();
		}
		cartasGiradasInARow++;
		messageErrorDone = 0;
		giro.play();
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
	document.querySelector("#countQuestions span").innerText = numPreguntas;
	document.querySelector('input[name="pwd"]').value = numPreguntas;
	if(elegida.getAttribute(selectedItem.id) == selectedItem.value){
		//win();
		//Pregunta acertada
		showLight(0);
		return true;
	}else{

		//Pregunta errónea
		showLight(1);
		return false;
	}
}

//Función que, una vez comprobado el juego, da la vuelta a la carta elegida y muestra la ventana para guardar datos y ranking
function win(){
	rotate(elegida);
	var modal = document.getElementById('myModal');
	modal.style.display = "block";
	document.getElementById("shownForm").style.display = "block";
	document.getElementById("hiddenForm").style.display = "block";
	document.getElementById("otherRank").style.display = "block";
	document.getElementById("record").style.display = "none";
	ganar.play();
}

function lose(){
	rotate(elegida);
	var lmodal = document.getElementById('myModalLose');
	lmodal.style.display =	"block";
	perder.play();

}
//Comprobación si los combos están correctamente seleccionados
function workCombo(form){
	var combos = document.getElementsByClassName("combo");
	
	if(cartasGiradasInARow == 0 && messageErrorDone == 1){
		alert("No has girado ninguna carta");
		messageErrorDone++;
	}else{
		if(checkCombo(combos)){
			//desaparece boton easy
			if (bloqueo==0){
			document.getElementById("easy").style.display= "none";
			}
			//Todo ok
			numPreguntas++;
			document.getElementById("mostrarPregunta").innerHTML = numPreguntas;
			checkMatch();
			messageErrorDone++;
			if(cartasGiradasInARow > 0){
				cartasGiradasInARow = 0;
			}
		}
	}
	document.getElementById('preguntar').disabled = true;
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

//Ruben
function bloquearEasy(){
	bloqueo=1;
	document.getElementById("easy").disabled= true;
}

function openModal(){
	document.getElementById("otherRank").style.display = "none";
	document.getElementById("formRank").style.display = "block";
	document.getElementById("shownForm").style.display = "none";
	window.frames['transFrame'].src = "load.php";
}

function closeWindow(){
	document.querySelector("#myModal").style.display = "none";
	var mod = document.querySelector("#windowContent");
	mod.classList.toggle("collapsed");
}

function reloadGame(){
	location.reload();
}

//Función que cierra la ventana de error
function closeWindowAlert(button){
	button.parentNode.style.display = "none";
}

//Función para cargar los datos de archivo a la base de datos de forma asíncrona
function loadData(){
	
	var elementsFrame = window.frames['transFrame'].document.getElementsByClassName("fila");
	var elementoActual = document.querySelectorAll("#tablaRecord tr");

	for(var i=0;i<elementoActual.length;i++){
		if(elementsFrame[i].childNodes[1].textContent != "" | elementsFrame[i].childNodes[3].textContent != ""){
			elementoActual[i].childNodes[1].innerHTML = elementsFrame[i].childNodes[1].textContent;
			elementoActual[i].childNodes[3].innerHTML = elementsFrame[i].childNodes[3].textContent;
		}
	}
}

function sendForm(){
	document.getElementById("shownForm").style.display = "block";
	document.getElementById("hiddenForm").style.display = "none";
}

function sendForm2(button){
	document.getElementById("record").style.display = "block";
	button.disabled = true;
	var mod = document.querySelector("#windowContent");
	mod.classList.toggle("collapsed");
	document.getElementById("cerrarRanking").style.display = "none";
	loadData();
	fondo();
}

function endOfGame(){
	var final = document.getElementsByName('front')[0];
	var carta_seleccionada =  document.getElementById('elegida');
	if(final.childNodes[1].firstChild.src == carta_seleccionada.src ){
		//alert('ganaste');
		win()
	}else{
		lose();
	}
}

function showLight(num){
	document.getElementById('true').style.display = "none";
	document.getElementById('false').style.display = "none";

	if (num == 0){
		document.getElementById('true').style.display = "inline";
	}
	else{
		document.getElementById('false').style.display = "inline";
	}
}

function showRanking(){
	document.getElementById("record").style.display = "block";
	document.getElementById("shownForm").style.display = "none";
	document.getElementById("hiddenForm").style.display = "none";
	document.getElementById("otherRank").style.display = "none";
	var modal = document.getElementById('myModal');
	modal.style.display = "block"; 
	var mod = document.querySelector("#windowContent");
	mod.classList.toggle("collapsed");
	document.getElementById("cerrarRanking").style.display = "inline";
}

//Fuegos Jose
function setup() {
	canvas = document.getElementById("canvas");
	setSize(canvas);
	ctx = canvas.getContext("2d");
	ctx.fillStyle = "#000000";
	ctx.fillRect(0, 0, width, height);
	fireworks.push(new Firework(Math.random()*(width-200)+100));
	window.addEventListener("resize",windowResized);
	document.addEventListener("click",onClick);
}

setTimeout(setup, 1);

function loop(){
	ctx.globalAlpha = 0.1;
	ctx.fillStyle = "#000000";
	ctx.fillRect(0, 0, width, height);
	ctx.globalAlpha = 1;

	for(let i=0; i<fireworks.length; i++){
		let done = fireworks[i].update();
		fireworks[i].draw();
		if(done) fireworks.splice(i, 1);
	}

	for(let i=0; i<particles.length; i++){
		particles[i].update();
		particles[i].draw();
		if(particles[i].lifetime>80) particles.splice(i,1);
	}

	if(Math.random()<1/60) fireworks.push(new Firework(Math.random()*(width-200)+100));
}

setInterval(loop, 1/60);

class Particle{
	constructor(x, y, col){
		this.x = x;
		this.y = y;
		this.col = col;
		this.vel = randomVec(2);
		this.lifetime = 0;
	}

	update(){
		this.x += this.vel.x;
		this.y += this.vel.y;
		this.vel.y += 0.02;
		this.vel.x *= 0.99;
		this.vel.y *= 0.99;
		this.lifetime++;
	}

	draw(){
		ctx.globalAlpha = Math.max(1-this.lifetime/80, 0);
		ctx.fillStyle = this.col;
		ctx.fillRect(this.x, this.y, 2, 2);
	}
}

class Firework{
	constructor(x){
		this.x = x;
		this.y = height;
		this.isBlown = false;
		this.col = randomCol();
	}

	update(){
		this.y -= 3;
		if(this.y < 350-Math.sqrt(Math.random()*500)*40){
			this.isBlown = true;
			for(let i=0; i<60; i++){
				particles.push(new Particle(this.x, this.y, this.col))
			}
		}
		return this.isBlown;
	}

	draw(){
		ctx.globalAlpha = 1;
		ctx.fillStyle = this.col;
		ctx.fillRect(this.x, this.y, 2, 2);
	}
}

function randomCol(){
	var letter = '0123456789ABCDEF';
	var nums = [];

	for(var i=0; i<3; i++){
		nums[i] = Math.floor(Math.random()*256);
	}

	let brightest = 0;
	for(var i=0; i<3; i++){
		if(brightest<nums[i]) brightest = nums[i];
	}

	brightest /=255;
	for(var i=0; i<3; i++){
		nums[i] /= brightest;
	}

	let color = "#";
	for(var i=0; i<3; i++){
		color += letter[Math.floor(nums[i]/16)];
		color += letter[Math.floor(nums[i]%16)];
	}
	return color;
}

function randomVec(max){
	let dir = Math.random()*Math.PI*2;
	let spd = Math.random()*max;
	return{x: Math.cos(dir)*spd, y: Math.sin(dir)*spd};
}

function setSize(canv){
	canv.style.width = (innerWidth) + "px";
	canv.style.height = (innerHeight) + "px";
	width = innerWidth;
	height = innerHeight;

	canv.width = innerWidth*window.devicePixelRatio;
	canv.height = innerHeight*window.devicePixelRatio;
	canvas.getContext("2d").scale(window.devicePixelRatio, window.devicePixelRatio);
}

function onClick(e){
	fireworks.push(new Firework(e.clientX));
}

function windowResized(){
	setSize(canvas);
	ctx.fillStyle = "#000000";
	ctx.fillRect(0, 0, width, height);
}

function fondo(){
	document.getElementById('canvas').style.display = 'inline';
}

function habilitarPregunta(){
	document.getElementById('preguntar').disabled = false;
}