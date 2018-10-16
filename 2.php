<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?
		//archivo imagen
		class Carta {
			public $id;
			public $imagen;
			public $gafas;
			public $pelo;
			public $genero;

			function __construct($id, $imagen,$gafas, $pelo, $genero){
				$this->id = $id;
				$this->imagen = $imagen;
				$this->gafas = $gafas;
				$this->pelo = $pelo;
				$this->genero = $genero;
			}
		}

		$config = specFile();
		$cartasText = specConfig();
		if($cartasText == false){
			echo "ERROR";
		}else if(!conf($cartasText,$config)){
			echo "ERROR1";
		}else if(!equal($cartasText)){
			echo "ERROR2";
		}else{
			echo "good";
		}


		function specFile(){
			$myfile = fopen("config.txt", "r") or die("Unable to open file!");
			while(!feof($myfile)) {
				$linea = fgets($myfile);
				if(!empty(trim($linea))){ //Comprobamos que la línea no esté vacía
					$lineaExp = explode(':', $linea); //Hacemos split para dividir key de value
					$lineaExp2 = explode("\r\n",$lineaExp[1]);
					$configKey = $lineaExp[0]; //Creamos la variable con la key
					$configValues = explode(';', $lineaExp2[0]); //Hacemos split para crear un array con los valores
					$config[$configKey] = $configValues; //Asignamos los valores a la key
				}
			}
			return $config;
			fclose($myfile);
		}

		function specConfig(){
			$cartasText = [];
			$file = file("1.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach($file as $linea){
				$img = explode(':',$linea);			
				$split1 = explode(';', $img[1]); //Mete en un array, por cada vuelta, un array con la línea anterior spliteada por punto y coma
				foreach($split1 as $value){
					$split2 = preg_split('/ +/', $value);
					$carta[$split2[0]] = $split2[1];
				}
				if(array_key_exists($img[0],$cartasText)){
					return false;
				}
				$cartasText[$img[0]] = $carta;
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

		//Función para comprobar que la configuración de las imágenes no está repetida
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