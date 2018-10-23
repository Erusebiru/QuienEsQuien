<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="js/script.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?	
		$config = specFile(); //Creamos el array de características.
		$cartasText = specConfig(); //Creamos el array con las cartas.

		if($cartasText == false){ //Si hubiese un nombre de imágen duplicado daría error.
			generarErrores(0);
		}else if(!conf($cartasText,$config)){ //Si hubiese una característica de una carta que no exista en config, daría error.
			generarErrores(2);
		}else if(!equal($cartasText)){ //Si dos cartas tuvieran las mismas características, daría error.
			generarErrores(1);
		}else{	//Si no entrase en ninguno de los errores, cargaría la página
			$keys = array_keys($cartasText);
			shuffle($keys);
			foreach ($keys as $key) {
				$cartas[$key] = $cartasText[$key];
			}
			$rand = array_rand($cartas); //Barajamos las cartas y nos seleccionará la key de la carta
			$seleccionada = $cartas[$rand]; //Sacamos las características de la carta
			?>
			<div id="container">
				<div id="left">
					<div class="flip-card">
						<div class="back-face-selected"></div>
						<div class="selected">
							<img src="Imagenes/<?=$rand?>" id="elegida" name="<?=$rand?>" gafas="<?=$seleccionada['gafas']?>" pelo="<?=$seleccionada['pelo']?>" genero="<?=$seleccionada['genero']?>">
						</div>
					</div>
					<div id="preguntas">
						<?$caracter = ["Gafas:","Color de Pelo:","Genero:"];
						echo "<form id='preguntas'>";
							$num = 0;
							foreach ($config as $key => $carta) {
								?><label><?=$caracter[$num]?></label>
								</br><select class="combo" id="<?=$key?>">"
								<option selected="selected" disabled="true">Selecciona una respuesta</option>
								<?
								foreach ($carta as $value) {
									echo "<option>".$value."</option>";
								}
								$num++;
								?>
								</select></br></br>
								
							<?}?>
						</form>
						<br><button onclick='workCombo()' id='preguntar'>Preguntar</button>
						<br><br><button id="easy" onclick="bloquearEasy()">Modo Easy</button>
					</div>
				</div>

				<div id="tablaCartas"><?
					echo "<table>";
					$contador = 0;
					foreach ($cartas as $key => $carta) {
						if($contador == 0){
							echo "<tr>";	
						}
						if($contador == 3){
							echo "</tr>";
							$contador = 0;
						}
						?><td>
							<div class="flip-card" onclick="rotate(this);">
								<div class="front-face imagen"><img src="Imagenes/<?=$key?>" id="<?=$key?>" gafas="<?=$carta['gafas']?>" pelo="<?=$carta['pelo']?>" genero="<?=$carta['genero']?>"></div>
								<div class="back-face"></div>
							</div>
						</td>
						<?$contador++;
					}
					?>
				</div>

				<div id="ventanaError" class="windowMessage">
					<h3 id="textError"></h3>
					<button id="cerrarVentana" onclick="closeWindow(this)">Cerrar</button>
				</div>
				<div id="ventanaRecord" class="windowMessage">
					<h2>Guardar Record</h2>
					<label for="nameRecord">Introduce tu nombre</label>
					<input type="text" id="nameRecord">
					<br><br>
					<button>Enviar</button>
				</div>
			</div>
		<?}

		//Función para crear el array de configuración
		function specFile(){
			$myfile = fopen("config.txt", "r") or die("Unable to open file!"); //Abrimos el archivo de configuración
			while(!feof($myfile)) {
				$linea = fgets($myfile); //Cargamos una línea por vuelta en la variable
				if(!empty(trim($linea))){ //Comprobamos que la línea no esté vacía
					$lineaExp = explode(':', $linea); //Hacemos split para dividir key de value
					$lineaExp2 = explode("\r\n",$lineaExp[1]); //Hacemos un split para eliminar el salto de carro y el salto de línea
					$configKey = $lineaExp[0]; //Creamos la variable con la key
					$configValues = explode(',', $lineaExp2[0]); //Hacemos split para crear un array con los valores
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
				$split1 = explode(',', $img[1]); //Mete en un array, por cada vuelta, un array con la línea anterior spliteada por punto y coma
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

		//Función que genera los archivos de error
		function generarErrores($numError){
			if ($numError == 0){
				//Un mismo nombre de imagen aparece dos veces en el archivo de configuracion
				$nombreFichero = fopen("Errores/duplicadas.txt", "w");
				fwrite($nombreFichero, "Error: El nombre de las cartas debe ser diferente");
				fclose($nombreFichero);
			}
			else if($numError ==  1){
				//Dos imagenes tienen las mismas caracteristicas
				$mismasC= fopen("Errores/igualCaracteristicas.txt", "w");
				fwrite($mismasC, "Error: Dos imagenes no pueden tener las mismas características entre si");
				fclose($mismasC);
			}
			else if($numError == 2){
				//Una caracteristica no aparece en el fichero config.txt
				$noFound= fopen("Errores/no_encontrada.txt", "w");
				fwrite($noFound, "Error: Característica no encontrada en el archivo config.txt");
				fclose($noFound);
			}
		}

	?>
</body>
</html>