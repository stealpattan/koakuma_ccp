<?php 
	
?>

<!DOCTYPE html>
<html lang='ja'>
	<head>
		<meta charset='utf8'>
		<title>管理者ページ</title>
		<link rel="shortcut icon" href="img/logo/tpu_logo.png">
	    <link rel="stylesheet" href="css/reset.css">
	    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
	    <link rel="stylesheet" href="css/common.css">
	    <link rel="stylesheet" href="css/home.css">
	    <link rel="stylesheet" type="text/css" href="css/intern.css">
	    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
	</head>
	<body>
		<?php require("header.php"); ?>
		<div class='manager'>
			<h5>ようこそ<?php echo "管理者"; ?>様</h5>
		</div>
		<div class="manager_page">
			<a href="" style='text-decoration:none'>
				<div class='manager manager_contents'>
					<h2>シルモク管理用ページ</h2>
					<p>シルモクに関する変更、情報の閲覧を行います</p>
				</div>
			</a>
			<a href="" style='text-decoration:none;'>
				<div class='manager manager_contents'>
					<h2>新着情報更新ページ</h2>
					<p>様々な新着情報の更新を行います</p>
				</div>
			</a>
		</div>
		<?php require("footer.php"); ?>
	</body>
</html>