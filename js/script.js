


function compruebaImagen(CartaElegida){
	var imgs = document.getElementsByClassName("imgTablero");
	for(var i=0;i<imgs.length;i++){
		imgs[i].parentNode.style.border = "";
	}
	if(CartaElegida.id == getCartaAsignada()){
		CartaElegida.parentNode.style.border = "2px solid green";
	}else{
		CartaElegida.parentNode.style.border = "2px solid red";
	}
	
}

function getCartaAsignada(){
	var cartaAsignada = document.getElementById("cartaAsignada").childNodes;
	//document.getElementById("text").innerText = cartaAsignada.length;
	var cartaID = cartaAsignada[1].id;
	return cartaID;
}