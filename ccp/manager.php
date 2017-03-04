<?php 
	session_start();

	echo "<pre>";
	var_dump($_SESSION);
	echo "</pre>";
	date_default_timezone_set('Asia/Tokyo');
	require('dbconnect.php');

	$error_array = array();
	$error_array['title_error'] = false;
	$error_array['date_error'] = false;
	$error_array['detail_error'] = false;

	if(!empty($_POST) && isset($_POST)){
		echo "<pre>";
			var_dump($_POST);
		echo "</pre>";
		if($_POST['event_title'] == '' || $_POST['event_title'] == null){
			$error_array['title_error'] = true;
		}
		if($_POST['month'] == '' || $_POST['month'] == null || $_POST['month'] < 1 || $_POST['month'] > 12 || $_POST['day'] == '' || $_POST['day'] == null || $_POST['day'] < 1 || $_POST['day'] > 31){
			$error_array['date_error'] = true;
		}
		if($_POST['time_detail'] == '' || $_POST['time_detail'] == null){
			$error_array['detail_error'] = true;
		}
		if($error_array['title_error'] == true || $error_array['date_error'] == true || $error_array['detail_error'] == true){
			find_error($error_array);
		}
		else{
			echo "none error";//ここで登録処理を行うようにすればいい
		}
	}

	$sql = sprintf("SELECT * FROM `news` WHERE 1 ORDER BY created DESC LIMIT 1");
	$rec = mysqli_query($db, $sql) or die(mysqli_error($db));
	$recent_news = mysqli_fetch_assoc($rec);

	var_dump($recent_news);

	function find_error($error_content){
		$_SESSION['event'] = $_POST;
		$_SESSION['error'] = $error_content;
		echo "<pre>";
			var_dump($_SESSION);
			var_dump($error_content);
		echo "</pre>";
		header('location:manager.php?page_type=new_event&error=exist');
	}
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
		<link rel="stylesheet" href="css/intern.css">
		<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
	</head>
	<body>
		<?php require("header.php"); ?>

		<!-- 管理者画面トップページ -->
		<?php if(empty($_GET['page_type']) && !isset($_GET['page_type'])): ?>
			<?php if(!empty($_SESSION['error']) && isset($_SESSION['error'])){$_SESSION['error'] = array();} ?>
			<?php if(!empty($_SESSION['event']) && isset($_SESSION['event'])){$_SESSION['event'] = array();} ?>
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
		<!-- 管理者画面トップページはここまで -->

		<?php if(!empty($_GET['page_type']) && isset($_GET['page_type'])): ?>
			<?php if($_GET['page_type'] == 'sirumoku'): ?>
				<!-- シルモクのデータを表示する場所 -->
			<?php endif; ?>

			<?php if($_GET['page_type'] == 'new_event'): ?>
				<!-- エラー発覚の際にここが処理されます -->
				<?php if(!empty($_GET['error']) && isset($_GET['error'])): ?>
					<div style='width:60%;' class='manager'>
						<h3 style='color:red;'>エラーが存在します</h3>
						<p style='color:red;'>
							<?php 
								if($_SESSION['error']['title_error']){echo "イベント名は正しく入力されていますか？";} 
								echo "<br>";
								if($_SESSION['error']['date_error']){echo " 日付が正しく入力されませんでした。再入力してください。";} 
								echo "<br>";
								if($_SESSION['error']['detail_error']){echo "詳細な時間は正しく指定されていますか？";} 
							?>
						</p>
					</div>
				<?php endif; ?>
				<!-- 以上エラー部 -->
				<!-- 以下新着情報コンテンツ部 -->
				<div class='new_event'>
					<table class='arrange_rows'>
						<tr>
							<th width='60%'>
								<div class='manager'>
									<h2>新着情報更新ページ</h2>
									<form action='manager.php?page_type=new_event' method='post'>
										<dl>
											<dt>イベント名：</dt>
											<dd>
												<input type='text' name='event_title' value='<?php 
																								if(!empty($_GET["error"]) && isset($_GET["error"])){
																									if($_SESSION["error"]["title_error"] == false){
																										echo $_SESSION["event"]["event_title"];
																									}
																								} 
																							?>'>
											</dd>
											<dt>日付：</dt>
											<dd>
												<input type='hidden' name='year' value='<?php echo date('Y'); ?>'>
												<input type='number' name='month' min='1' max='12' value='<?php
																											if(!empty($_GET["error"]) && isset($_GET["error"])){
																												if($_SESSION["error"]["date_error"] == false){
																													echo $_SESSION["event"]["month"];
																												}
																											}
																											else{
																												echo date("m"); 
																											}
																										?>'>月 
												<input type='number' name='day' min='1' max='31' value='<?php 
																											if(!empty($_GET["error"]) && isset($_GET["error"])){
																												if($_SESSION["error"]["date_error"] == false){
																													echo $_SESSION["event"]["day"];
																												}
																											}
																											else{
																												echo date("d");
																											} 
																										?>'>日	
											</dd>
											<dt>詳細な時間など</dt>
											<dd>
												<input type='text' name='time_detail' value='<?php 
																								if(!empty($_GET["error"]) && isset($_GET["error"])){
																									if($_SESSION["error"]["detail_error"] == false){
																										echo $_SESSION["event"]["time_detail"];
																									}
																								}
																							?>'>
											</dd>
											<dt>イベント詳細などコメント</dt>
											<dd>
												<textarea name='comment' cols='50' rows='5'><?php 
																								if(!empty($_GET['error']) && isset($_GET['error'])){
																									echo $_SESSION['event']['comment'];
																								}
																							?></textarea>
											</dd>
											<dt>イベント区分</dt>
											<dd>
												<select name='event_type' class='manager_contents'>
													<option>指定なし</option>
													<option>インターンシップ</option>
													<option>キャリア形成論</option>
													<option>就職支援</option>
													<option>その他お知らせ</option>
												</select>
											</dd>
											<dt>入力内容に間違いはありませんか？</dt>
											<dd>
												<input type='submit' value='登録' class='manager_contents'>
												<input type='button' value='戻る' class='manager_contents' onclick='history.back()'>
											</dd>
										</dl>
									</form>
									<div style='width:30%;' class='manager'>
										<a href="manager.php"> <-管理者画面へ </a>
									</div>
								</div>
							</th>
							<th>
								<div class='recent_update'>
									<h2>最近の更新</h2>
									<dl>
										<dt>イベント名：</dt>
										<dd><?php echo $recent_news['title']; ?></dd>
										<dt>日付：</dt>
										<dt><?php echo $recent_news['month'] . "月" . $recent_news['day'] . "日"; ?></dt>
										<dt>詳細な時間：</dt>
										<dd><?php echo $recent_news['time_detail']; ?></dd>
										<dt>コメント：</dt>
										<dd><?php echo $recent_news['text']; ?></dd>	
									</dl>
								</div>
							</th>
						</tr>
					</table>
				</div>
				<!-- 以上新着情報コンテンツ部 -->
			<?php endif; ?>
		<?php endif; ?>
		<?php require("footer.php"); ?>
	</body>
</html>