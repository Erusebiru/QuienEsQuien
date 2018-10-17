<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="js/script.js" defer></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?
		$config = specFile(); //Creamos el array de características.
		$cartasText = specConfig(); //Creamos el array con las cartas.

		if($cartasText == false){ //Si hubiese un nombre de imágen duplicado daría error.
			echo "ERROR";
		}else if(!conf($cartasText,$config)){ //Si hubiese una característica de una carta que no exista en config, daría error.
			echo "ERROR1";
		}else if(!equal($cartasText)){ //Si dos cartas tuvieran las mismas características, daría error.
			echo "ERROR2";
		}else{	//Si no entrase en ninguno de los errores, cargaría la página
			?>	
				<div id="ventanaError">
					<h3 id="textError"></h3>
					<button id="cerrarVentana" onclick="closeWindow(this)">Cerrar</button>
				</div>
			<?
		}


		//Función para crear el array de configuración
		function specFile(){
			$myfile = fopen("config.txt", "r") or die("Unable to open file!"); //Abrimos el archivo de configuración
			while(!feof($myfile)) {
				$linea = fgets($myfile); //Cargamos una línea por vuelta en la variable
				if(!empty(trim($linea))){ //Comprobamos que la línea no esté vacía
					$lineaExp = explode(':', $linea); //Hacemos split para dividir key de value
					$lineaExp2 = explode("\r\n",$lineaExp[1]); //Hacemos un split para eliminar el salto de carro y el salto de línea
					$configKey = $lineaExp[0]; //Creamos la variable con la key
					$configValues = explode(';', $lineaExp2[0]); //Hacemos split para crear un array con los valores
					$config[$configKey] = $configValues; //Asignamos los valores a la key
				}
			}
			return $config; //Devolvemos el array con la configuración del archivo
			fclose($myfile); //Cerramos el archivo
		}

		//Función para crear el array de cartas
		function specConfig(){
			$cartasText = []; //Incializamos el array que contendrá las cargas
			$file = file("cartas.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); //Abrimos el archivo ignorando las líneas vacías y el final de línea (salto de carro y salto de línea)
			foreach($file as $linea){
				$img = explode(':',$linea); //Hacemos un split separando el nombre de la imágen y los valores
				$imgName = $img[0]; //Instanciamos el nombre de la imagen
				$imgValues = $img[1]; //Instanciamos los valores 
				$split1 = explode(';', $img[1]); //Mete en un array, por cada vuelta, un array con la línea anterior spliteada por punto y coma
				foreach($split1 as $value){
					$split2 = preg_split('/ +/', $value);
					$carta[$split2[0]] = $split2[1];
				}
				if(array_key_exists($imgName,$cartasText)){
					return false;
				}
				$cartasText[$imgName] = $carta;
			}
			return $cartasText;
		}

		function conf($cartasText, $config){
			foreach($cartasText as $cartaText){
				foreach($cartaText as $key => $value){
					if(checkConf($value,$config) === false){
						return false;
					}
				}
			}
			return true;
		}

		function checkConf($value,$config){
			foreach($config as $key => $conf){
				if(in_array($value,$conf)){
					return true;
				}
			}
			return false;
		}

		//Función para comprobar que la configuración de las imágenes no está repetida.
		//Devolverá false si encuentra una repetida o true si está todo correcto.
		function equal($cartasText){
			$equalCounter = 0;
			foreach($cartasText as $cartaText){
				if(checkEqual($cartasText,$cartaText)){
					if($equalCounter > 0){
						return false;
					}
					$equalCounter++;
				}
			}
			return true;
		}

		function checkEqual($cartasText, $cartaText){
			foreach($cartasText as $carta2Text){
				$count = 0;
				if($cartaText['genero'] == $carta2Text['genero']){
					$count++;
				}
				if($cartaText['pelo'] == $carta2Text['pelo']){
					$count++;
				}
				if($cartaText['gafas'] == $carta2Text['gafas']){
					$count++;
				}
				if($count == 3){
					return true;
				}else{
					return false;
				}
			}
		}
	?>
	
</body>
</html>