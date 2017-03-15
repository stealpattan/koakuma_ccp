<?php
	session_start();
	date_default_timezone_set('Asia/Tokyo');
	require('dbconnect.php');

	$error_array = array();
	$error_array['title_error'] = false;
	$error_array['date_error'] = false;
	$error_array['detail_error'] = false;

	if(!empty($_GET['page_type']) && isset($_GET['page_type'])){
		//新着情報の追加・更新の場合以下の比較処理がされます
		if($_GET['page_type'] == "new_event"){
			//新着情報の追加・更新画面に表示する’最近の更新’のデータを取得しています
			$sql = sprintf("SELECT * FROM `news` WHERE 1 ORDER BY created DESC LIMIT 1");
			$rec = mysqli_query($db, $sql) or die(mysqli_error($db));
			$recent_news = mysqli_fetch_assoc($rec);
			if(!empty($_POST) && isset($_POST)){
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
					$_SESSION['regest_event'] = $_POST;
					$alert = sprintf('<script type="text/javascript">
											if(window.confirm("登録内容をご確認ください\n\nタイトル: %s \n日付: %s 月 %s 日 \n詳細な時間: %s \nイベント詳細: %s \nイベント区分: %s \n対象学年: %s")){
												location.href = "manager.php?page_type=regestration";
											}
											else{
												history.back();
											}
										</script>',
										$_POST['event_title'],$_POST['month'],
										$_POST['day'],$_POST['time_detail'],
										$_POST['comment'],$_POST['event_type'],
										$_POST['target']
									);
					echo $alert;
				}
			}	
		}
		else if($_GET['page_type'] == 'sirumoku'){
			$sirumoku_data = array();
			$sql = sprintf('SELECT * FROM `sirumoku_data` WHERE 1');
			$record = mysqli_query($db,$sql) or die(mysqli_error($db));
			while($rec = mysqli_fetch_assoc($record)){
				$sirumoku_data[] = $rec;
			}
			$sql = sprintf('SELECT event_date,COUNT(student_number) FROM `sirumoku_entry` GROUP BY event_date');
			$record = mysqli_query($db,$sql) or die(mysqli_error($db));
			$total = array();
			while($rec = mysqli_fetch_assoc($record)){
				$total[] = $rec;
			}
			$sql = sprintf('SELECT COUNT(id) FROM `sirumoku_entry`');
			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
			$sum = mysqli_fetch_assoc($record);
		}
	}
	//新着情報追加・更新の際にエラーが発見されると以下が処理されます。
	function find_error($error_content){
		$_SESSION['event'] = $_POST;
		$_SESSION['error'] = $error_content;
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
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
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
			<div class='manager manager_page'>
				<h5>ようこそ<?php echo "管理者"; ?>様</h5>
			</div>
			<div class="manager_page">
				<a href="manager.php?page_type=sirumoku" style='text-decoration:none'>
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
			<!-- シルモクデータ表示 -->
			<?php if($_GET['page_type'] == 'sirumoku'): ?>
				<div class=''>
					<table width='70%' class='manager'>
						<tr>
							<th>開催日</th>
							<th>時間</th>
							<th>エントリー企業様</th>
							<th>申し込み総数</th>
						</tr>
						<?php foreach($sirumoku_data as $sirumoku_data): ?>
							<tr>
								<td><?php echo $sirumoku_data['date']; ?></td>
								<td><?php echo $sirumoku_data['start-time']; ?>~<?php echo $sirumoku_data['finish-time']; ?></td>
								<td><?php echo $sirumoku_data['name_company']; ?></td>
								<td>
									<?php 
										for ($i=0; $i < count($total); $i++) { 
											if($total[$i]['event_date'] == $sirumoku_data['date']){
												echo $total[$i]['COUNT(student_number)'];
											}
										}
									?>
								</td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<th>開催日</th>
							<th>時間</th>
							<th style='text-align:right;'>合計</th>
							<th><?php echo $sum['COUNT(id)']; ?></th>
						</tr>
					</table>
					<div style='width:30%;' class='manager'>
						<a href="manager.php"> <-管理者画面へ </a>
					</div>
				</div>
			<?php endif; ?>
			<!-- 以上シルモクデータ表示部分 -->

			<!-- 以下新着情報の更新画面 -->
			<?php if($_GET['page_type'] == 'new_event'): ?>
				<!-- エラー発覚の際にここが処理されます -->
				<?php if(!empty($_GET['error']) && isset($_GET['error'])): ?>
					<div style='width:60%;' class='manager'>
						<h3 style='color:red;'>エラーが存在します</h3>
						<p style='color:red;'>
							<?php
								if($_SESSION['error']['title_error']){echo "イベント名は正しく入力されていますか？";}
								echo "<br>";								if($_SESSION['error']['date_error']){echo " 日付が正しく入力されませんでした。再入力してください。";}
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
							<th width='50%'>
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
												<input type='hidden' name='year' value='<?php echo (int)date('Y'); ?>'>
												<input type='number' name='month' min='1' max='12' value='<?php
																											if(!empty($_GET["error"]) && isset($_GET["error"])){
																												if($_SESSION["error"]["date_error"] == false){
																													echo $_SESSION["event"]["month"];
																												}
																											}
																											else{
																												echo (int)date("m"); 
																											}
																										?>'>月
												<input type='number' name='day' min='1' max='31' value='<?php
																											if(!empty($_GET["error"]) && isset($_GET["error"])){
																												if($_SESSION["error"]["date_error"] == false){
																													echo $_SESSION["event"]["day"];
																												}
																											}
																											else{
																												echo (int)date("d");
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
											<dt>対象学年</dt>
											<dd>
												<select name='target' class='manager_contents'>
													<option>全学年</option>
													<option>学部1年生(B1)</option>
													<option>学部2年生(B2)</option>
													<option>学部3年生(B3)</option>
													<option>学部4年生(B4)</option>
													<option>大学院1年生(B1)</option>
													<option>大学院2年生(B2)</option>
													<option>博士課程</option>
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
										<dt>イベント区分：</dt>
										<dd><?php echo $recent_news['event_kind']; ?></dd>
									</dl>
								</div>
							</th>
						</tr>
					</table>
				</div>
				<!-- 以上新着情報コンテンツ部 -->
			<?php endif; ?>
			<!-- 以上新着情報更新部 -->
			<?php if($_GET['page_type'] == "regestration"): ?>
				<?php 
					$sql = sprintf("INSERT INTO `news`(`year`,`month`,`day`,`title`,`time_detail`,`text`,`event_kind`,`target`,`created`)
													VALUES('%s','%s','%s','%s','%s','%s','%s','%s',NOW())",
																	$_SESSION['regest_event']['year'],$_SESSION['regest_event']['month'],$_SESSION['regest_event']['day'],
																	$_SESSION['regest_event']['event_title'],$_SESSION['regest_event']['time_detail'],$_SESSION['regest_event']['comment'],
																	$_SESSION['regest_event']['event_type'],$_SESSION['regest_event']['target']);
					mysqli_query($db, $sql) or die(mysqli_error($db));
					$_SESSION['regest_event'] = array();
					header('location: manager.php?page_type=new_event');
					exit();
				?>
			<?php endif; ?>
		<?php endif; ?>
		<?php require("footer.php"); ?>
	</body>
</html>
