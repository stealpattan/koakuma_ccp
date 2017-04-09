<?php  

	require('dbconnect.php');
	require('function.php');
	if(!empty($_GET['id']) && isset($_GET['id'])){
		$sql = sprintf('SELECT * FROM `news` WHERE id=%s',$_GET['id']);
		$record = mysqli_query($db,$sql) or die(mysqli_error($db));
		$data = mysqli_fetch_assoc($record);
	}
	else{
		header("home.php");
		exit();
	}
?>

<DOCTYPE html>
<html lang="ja">
<head>
	<meta charset='utf-8'>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/intern.css">
</head>
<body>
	<?php require("header.php"); ?>
	<div style='width:70%' class='manager'>
		<h1 style='font-size:200%;border-bottom:3px solid #6eb7db;color:black;'><?php echo $data['title']; ?></h1>
		<dl>
			<div style='background:white;border-radius:20px;'>
				<br>
				<dt style='color:black;'>開催日</dt>
				<dd style='color:black;'>
					<?php echo $data['year'] . "年" . $data['month'] . "月" . $data['day'] . "日"; ?>
				</dd>
				<dt style='color:black;'>詳細な時間</dt>
				<dd style='color:black;'>
					<?php echo $data['time_detail']; ?>
				</dd>
				<dt style='color:black;'>対象学年</dt>
				<dd style='color:black;'><?php echo $data['target']; ?></dd>
				<dt style='color:black;'>その他留意事項</dt>
				<dd style='color:black;'>
					<?php echo $data['comment']; ?>
				</dd>
				<br>
			</div>
		</dl>
	</div>
	<?php require("footer.php"); ?>
</body>
</html>