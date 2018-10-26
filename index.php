<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src="js/script.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?	
		session_start();
		$config = specFile(); //Creamos el array de características.
		$cartasText = specConfig(); //Creamos el array con las cartas.

		if($cartasText == false){ //Si hubiese un nombre de imágen duplicado daría error.
			generarErrores(0);
		}else if(!conf($cartasText,$config)){ //Si hubiese una característica de una carta que no exista en config, daría error.
			generarErrores(2);
		}else if(!equal($cartasText)){ //Si dos cartas tuvieran las mismas características, daría error.
			generarErrores(1);
		}else{	//Si no entrase en ninguno de los errores, cargaría la página
			if(isset($_SESSION['cartas'])){
				$rand = $_SESSION['selectedCardKey'];
				$seleccionada = $_SESSION['selectedCardValues'];
				$cartas = $_SESSION['cartas'];
			}else{
				$keys = array_keys($cartasText);
				shuffle($keys);
				foreach ($keys as $key) {
					$cartas[$key] = $cartasText[$key];
				}
				$rand = array_rand($cartas); //Barajamos las cartas y nos seleccionará la key de la carta
				$seleccionada = $cartas[$rand]; //Sacamos las características de la carta

				$_SESSION['selectedCardKey'] = $rand;
				$_SESSION['selectedCardValues'] = $seleccionada;
				$_SESSION['cartas'] = $cartas;
			}
			echo $rand;
			?>
			<canvas id="canvas"></canvas>
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
						?><form id="preguntas"><?
							$num = 0;
							foreach ($config as $key => $carta) {
								?><div class="preguntaForm"><?=$caracter[$num]?></div>
								<select class="combo" id="<?=$key?>" onchange="habilitarPregunta()">
								<option selected="selected" disabled="true">Selecciona una respuesta</option>
								<?
								foreach ($carta as $value) {
									echo "<option>".$value."</option>";
								}
								$num++;
								?>
								</select></br></br>
							<?}?>
							<button type="button" onclick='workCombo(this.form)' id='preguntar' disabled>Preguntar</button>
						</form>
						<br><button id="showRanking" onclick="showRanking()">Mostrar Ranking</button><br>
						<br><button id="easy" onclick="bloquearEasy()">Modo Easy</button>
					</div>
					<br>
					<div id="marcador" class="preguntaForm">
						
						Numero de preguntas: <label id="mostrarPregunta">0</label>
					</div>
					<div class="preguntaForm">
						<div id="true">
							Correcto<img id="correcto" class="light" src="Imagenes/circle_green.png">
						</div>

						<div id="false">
							Incorrecto<img id="error" class="light" src="Imagenes/circle.png">
						</div>

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
							<div class="flip-card" name="front" onclick="rotate(this);">
								<div class="front-face imagen"><img src="Imagenes/<?=$key?>" id="<?=$key?>" gafas="<?=$carta['gafas']?>" pelo="<?=$carta['pelo']?>" genero="<?=$carta['genero']?>"></div>
								<div class="back-face"></div>
							</div>
						</td>
						<?$contador++;
					}
					echo "</table>";
					?>
				</div>

				<div id="ventanaError" class="windowMessage">
					<h3 id="textError"></h3>
					<button id="cerrarVentana" onclick="closeWindowAlert(this)">Cerrar</button>
				</div>

				<div id="myModalLose" class="modal">
					<div class="modal-content" name="loser">
						<div class="Loser">
							<h2>¡Has perdido!</h2>
							<a href="logout.php"><button onclick="reloadGame()">Volver a Jugar</button></a>
						</div>
					</div>
				</div>
		

				<div id="myModal" class="modal">
					<div class="modal-content">
						<div id="RankWindow">
							<div id="otherRank">
								<h2>¡Has ganado!</h2>
								<span>¿Deseas guardar tus datos?</span>
								<button onclick="openModal()">Sí</button>
								<a href="logout.php"><button onclick="reloadGame()">Volver a Jugar</button></a>
								<br><br>
							</div>
							<div id="formRank">
								<form target="transFrame" method="POST" class="EditName" id="reportEdit" action="load.php">
									<div id="hiddenForm">
										<h3>Introduce tus datos</h3>
										<div id="inputName">
											<label  for="inputName">Introduce tu nombre: <input type="text" name="transDesc"></label>
										
											<input type="hidden" id="custId" name="pwd" value="0">
											<label id="countQuestions"><b>Puntuación: <span>0</span></b></label>
											<input type="submit" name="submit" value="Submit" onclick="sendForm()"/>
										</div>
									</div>
								</form>
									<div id="shownForm">
										<h3>Mostrar ranking</h3>
										<input type="submit" name="submit" value="Mostrar Ranking" onclick="sendForm2(this)"/>
										<a href="logout.php"><button id="reiniciarJuego" onclick="reloadGame()">Volver a Jugar</button></a>
										<br><br>
									</div>
								
								<iframe name="transFrame" id="transFrame">yjd</iframe>
							</div>
							
							<div id="record">
								<h3>Record de jugadores</h3>
								
								<table id='tablaRecord'>
								<?
									$ranking = specRanking();
									for($i=0;$i<10;$i++){
										if(isset($ranking[$i])){
											$rankingPersona = $ranking[$i];
										}else{
											$rankingPersona = ['-','-'];
										}
									?>
										<tr class="fila">
											<td><?=$rankingPersona[0]?></td>
											<td><?=$rankingPersona[1]?></td>
										</tr>
									<?}?>
								</table>
								<br><button id="cerrarRanking" onclick="closeWindow()">Cerrar ventana</button>
								<br>
							</div>
						</div>
					</div>
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

		function specRanking(){
			$ranking = [];
			$file = file("ranking.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach($file as $linea){
				$persona = explode(':',$linea);
				$ranking[] = $persona;
			}
			if(null !== $ranking){
				$j=0;
				$flag = true;
				$temp=0;

				while ( $flag ){
	  				$flag = false;
	  				for( $j=0;  $j < count($ranking)-1; $j++){
	    				if ( $ranking[$j][1] > $ranking[$j+1][1] ){
	      					$temp = $ranking[$j];
	      					//swap the two between each other
	      					$ranking[$j] = $ranking[$j+1];
	      					$ranking[$j+1]=$temp;
	      					$flag = true; //show that a swap occurred
						}
	  				}
				}
			}
			
			return $ranking;
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
				echo "<h1>Error: El nombre de las cartas debe ser diferente</h1>";
				fclose($nombreFichero);
			}
			else if($numError ==  1){
				//Dos imagenes tienen las mismas caracteristicas
				$mismasC= fopen("Errores/igualCaracteristicas.txt", "w");
				fwrite($mismasC, "Error: Dos imagenes no pueden tener las mismas características entre si");
				echo "<h1>Error: Dos imagenes no pueden tener las mismas características entre si</h1>";
				fclose($mismasC);
			}
			else if($numError == 2){
				//Una caracteristica no aparece en el fichero config.txt
				$noFound= fopen("Errores/no_encontrada.txt", "w");
				fwrite($noFound, "Error: Característica no encontrada en el archivo config.txt");
				echo "<h1>Error: Característica no encontrada en el archivo config.txt</h1>";
				fclose($noFound);
			}
		}
	?>
</body>
</html>