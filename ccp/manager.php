<?php 
	date_default_timezone_set('Asia/Tokyo');
	require('dbconnect.php');
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
		<?php if(empty($_GET['page_type']) && !isset($_GET['page_type'])): ?>
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
				<a href="manager.php?page_type=new_event" style='text-decoration:none;'>
					<div class='manager manager_contents'>
						<h2>新着情報更新ページ</h2>
						<p>様々な新着情報の更新を行います</p>
					</div>
				</a>
			</div>
		<?php endif; ?>
		<?php if(!empty($_GET['page_type']) && isset($_GET['page_type'])): ?>
			<?php if($_GET['page_type'] == 'sirumoku'): ?>
				<!-- シルモクのデータを表示する場所 -->
			<?php endif; ?>
			<?php if($_GET['page_type'] == 'new_event'): ?>
				<!-- 新着情報の更新をここから行います -->
				<div class='manager'>
					<h2>新着情報更新ページ</h2>
					<form action='manager.php' method='post'>
						<dl>
							<dt>イベント名：</dt>
							<dd>
								<input type='text' name='event_title'>
							</dd>
							<dt>日付：</dt>
							<dd>
								<input type='hidden' value='<?php echo date('Y'); ?>'>
								<input type='number' name='month' min='1' max='12' value='<?php echo date('m'); ?>'>月 
								<input type='number' name='day' min='1' max='31' value='<?php echo date('d'); ?>'>日	
							</dd>
							<dt>詳細な時間など</dt>
							<dd>
								<input type='text' name='time_detail'>
							</dd>
							<dt>イベント詳細などコメント</dt>
							<dd>
								<textarea name='comment' cols='50' rows='5'></textarea>
							</dd>
							<dt>入力内容に間違いはありませんか？</dt>
							<dd>
								<input type='submit' value='登録' class='manager_contents'>
								<input type='button' value='戻る' class='manager_contents' onclick='history.back()'>
							</dd>
						</dl>
					</form>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php require("footer.php"); ?>
	</body>
</html>