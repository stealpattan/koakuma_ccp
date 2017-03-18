<?php 	
	session_start();
	require("../dbconnect.php");
	$year = date('Y');

	$i = 0;
	if(!empty($_POST) && isset($_POST)){
		if (!empty($_POST['company_name']) && !empty($_POST['business_type']) &&  
			isset($_POST['company_name']) && isset($_POST['business_type'])) {
			//項目追加用のポスト送信がされていた場合セッションの配列としてブラウザに保存しておきます
			echo "<pre>";
			var_dump($_POST);
			echo "</pre>";	
			if($_POST["company_name"] != "" && $_POST["business_type"] != ""){
				$i = $_POST['regestration_number'];
				$_SESSION["regestration_number" . $i]["company_name"] = $_POST["company_name"];
				$_SESSION["regestration_number" . $i]["business_type"] = $_POST["business_type"];

				for ($j=$year; $j > $year-5; $j--) { 
					$_SESSION['regestration_number' . $i]['nendo_' . $j] = sprintf('%s_%s', $j, $_POST['nendo_' . $j]);
					echo $_SESSION['regestration_number' .$i]['nendo_' . $j];
				}
				$rege_year = array();
				for ($j=0; $j < 5; $j++) { 
					$rege_year[$j] = $year - $j;
				}

				$_SESSION['regestration_number' . $i]['amount_of_employer'] = sprintf('%s,%s,%s,%s,%s',
																				$_SESSION['regestration_number' . $i]['nendo_' . $rege_year[0]],
																				$_SESSION['regestration_number' . $i]['nendo_' . $rege_year[1]],
																				$_SESSION['regestration_number' . $i]['nendo_' . $rege_year[2]],
																				$_SESSION['regestration_number' . $i]['nendo_' . $rege_year[3]],
																				$_SESSION['regestration_number' . $i]['nendo_' . $rege_year[4]]
																			);

				$_SESSION["reg_num"] = $i;
			}
		}
		//登録ボタンが押された時の処理
		else if(!empty($_POST['regestration_do']) && isset($_POST['regestration_do'])){
			if($_POST['regestration_do'] != null){
				for ($j=0; $j <= $_SESSION['reg_num']; $j++) { 
					$sql = sprintf(
		 				'INSERT INTO `intern_datas`( `company_name`, `business_type`, `number_of_employer`, `created`) 
		 				VALUES ("%s", "%s", "%s", NOW());' ,
		 				 $_SESSION["regestration_number" . $j]["company_name"], 
		 				 $_SESSION["regestration_number" . $j]["business_type"],
		 				 $_SESSION['regestration_number' . $j]['amount_of_employer']
		 			);
		 			$record = mysqli_query($db, $sql) or die(mysqli_error());
				}
				$_SESSION = array();
			}
		}
		else{
			echo "正しく入力してください";
		}
	}
  
	echo "<pre>";
//	var_dump($_SESSION);
	echo "</pre>";
?>

<!DOCTYPE html>
<html>
<head>
	<title>input page</title>
</head>
<body>
	<form action="input_page.php" method="post">
		<div>企業名:<input type="text" name="company_name"></div>
		<br>
		<div>職種:<input type="text" name="business_type"><div>
		<br>
		<div>就職人数:</div>
		<?php
			for ($i=0; $i < 5; $i++) { 
				$nendo = $year - $i;
			 	echo "<div>";
			 		echo $nendo . "年度";
			 		$tag = sprintf('<input type="number" name="nendo_%s" size="1" value="0">', $nendo);
			 		//$hide = sprintf('<input type="hidden" name="%s_nen" value="%s"', $nendo, $nendo);
			 		echo $tag;
			 		//echo $hide;
			 		echo "人";
				echo "</div>";
			 } 
		 ?>
		 <div><input type = "hidden" name = "regestration_number" 
			value = "<?php 
						if (empty($_SESSION['reg_num']) && !isset($_SESSION['reg_num'])) {
							echo 0;
						}
						else{
							$number = $_SESSION['reg_num'] + 1;
							echo $number;
						}
					 ?>">
		</div>
		<input type="submit" value="追加">
		<input type="reset" value="再入力">
	</form> 
	<h3>登録内容を確定してデータベースに登録するには下の登録ボタンをクリックして下さい</h3>
	<form action="input_page.php" method="post">
		<input type = "hidden" name = "regestration_do" value = "do_regestration">
		<input type = "submit" value = "登録">
	</form>
	<h3>現在の登録待ちデータ</h3>
	<?php 
		echo "<pre>";
		var_dump($_SESSION);
		echo "</pre>";
	 ?>
</body>
</html>