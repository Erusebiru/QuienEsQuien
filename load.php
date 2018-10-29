<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
		<?
		
			$file= "ranking.txt";
			if(null !== $_POST['transDesc'] | null !== $_POST['pwd']){
				$persona = $_POST['transDesc'].":".$_POST['pwd'].PHP_EOL;
			}else{
				$persona = "-:-";
			}
			
			file_put_contents($file, $persona, FILE_APPEND | LOCK_EX);

			?>
			<table id='tablaRecord'>
			<?
				$ranking = specRanking();
				for($i=0;$i<10;$i++){
					if(isset($ranking[$i]) || $ranking == ""){
						$rankingPersona = $ranking[$i];
					}else{
						$rankingPersona = ['-','-'];
					}
					?>
						<tr class="fila">
							<td><?=$rankingPersona[0]?></td>
							<td><?=$rankingPersona[1]?></td>
						</tr>
					<?
				}
			?>
			</table>

			<?
			function specRanking(){
				$file = file("ranking.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				foreach($file as $linea){
					$persona = explode(':',$linea);
					$ranking[] = $persona;
					//$ranking[$persona[0]] = $persona[1];
				}

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
				return $ranking;
			}
		
		
		?>
</body>
</html>