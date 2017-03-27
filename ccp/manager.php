<?php
	session_start();
	date_default_timezone_set('Asia/Tokyo');

	require('dbconnect.php');
	require('function.php');
	// ユーザ認証部分
	if(!empty($_POST) && isset($_POST)){
		if(!empty($_POST['user_name']) && isset($_POST['user_name'])){
			if(!empty($_POST['pass']) && isset($_POST['pass'])){
				if(log_in($_POST['user_name'], $_POST['pass']) == true){
					$_SESSION['manager_login'] = true;
				}
			}
		}
	}
	$error_array = array();
	$error_array['title_error'] = false;
	$error_array['date_error'] = false;
	$error_array['detail_error'] = false;
	$rewrite = false;
	if(!empty($_GET['page_type']) && isset($_GET['page_type'])){
		//新着情報の追加・更新の場合以下の比較処理がされます
		if($_GET['page_type'] == "new_event"){
			//新着情報のデータ更新のためのデータ取得を行います
			if(!empty($_GET['rewrite']) && isset($_GET['rewrite'])){
				if(empty($_GET['error']) && !isset($_GET['error'])){
					$sql = 'SELECT * FROM `news` WHERE id=' . $_GET['rewrite'];
					$record = mysqli_query($db,$sql) or die(mysqli_error($db));
					$_SESSION['event'] = mysqli_fetch_assoc($record);
				}
				$rewrite = true;
			}
			else{
				$rewrite = false;
			}
			//新着情報の追加・更新画面に表示する’最近の更新’のデータを取得しています
			$sql = sprintf("SELECT * FROM `news` WHERE 1 ORDER BY created DESC LIMIT 1");
			$rec = mysqli_query($db, $sql) or die(mysqli_error($db));
			$recent_news = mysqli_fetch_assoc($rec);
			// フォームからのデータが存在するとき、以下が処理されます
			if(!empty($_POST) && isset($_POST)){
				if($_POST['title'] == '' || $_POST['title'] == null){
					$error_array['title_error'] = true;
				}
				if($_POST['month'] == '' || $_POST['month'] == null || $_POST['month'] < 1 || $_POST['month'] > 12 || $_POST['day'] == '' || $_POST['day'] == null || $_POST['day'] < 1 || $_POST['day'] > 31){
					$error_array['date_error'] = true;
				}
				if($_POST['time_detail'] == '' || $_POST['time_detail'] == null){
					$error_array['detail_error'] = true;
				}

				if($error_array['title_error'] == true || $error_array['date_error'] == true || $error_array['detail_error'] == true){
					if($rewrite == true){
						newEvent_registration_error($error_array, $_GET['rewrite']);
					}
					else{
						newEvent_registration_error($error_array, "none");
					}
				}
				// 入力エラーが存在しなかった場合
				else{
					$_SESSION['event'] = array();
					$_SESSION['event'] = $_POST;
					if(!empty($_GET['rewrite']) && isset($_GET['rewrite'])){
						$location = 'update_news';
						$_SESSION['event']['id'] = $_GET['rewrite'];
					}
					else{
						$location = 'registration';
					}
					$alert = sprintf('<script type="text/javascript">
											if(window.confirm("登録内容をご確認ください\n\nタイトル: %s \n日付: %s 月 %s 日 \n詳細な時間: %s \nイベント詳細: %s \nイベント区分: %s \n対象学年: %s")){
												location.href = "manager.php?page_type=%s";
											}
											else{
												history.back();
											}
										</script>',
										$_POST['title'],$_POST['month'],
										$_POST['day'],$_POST['time_detail'],
										$_POST['comment'],$_POST['event_type'],
										$_POST['target'],
										$location
									);
					echo $alert;
				}
			}
		}
		// シルモクページ部分
		else if($_GET['page_type'] == 'sirumoku'){
			if(!empty($_GET['delete']) && isset($_GET['delete'])){
				$sql = sprintf("DELETE FROM `sirumoku_data` WHERE id='%s'",$_GET['delete']);
				mysqli_query($db,$sql) or die(mysqli_error($db));
				header('location:manager.php?page_type=sirumoku');
				exit();
			}
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
			// シルモクページからのフォーム検出
			if(!empty($_POST) && isset($_POST)){
				// 未入力項目検出部分
				if($_POST['year'] == '' || $_POST['year'] == null ||
					$_POST['month'] == '' || $_POST['month'] == null || $_POST['month'] > 12 || $_POST['month'] < 1 ||
					$_POST['day'] == '' || $_POST['day'] == null || $_POST['day'] > 31 || $_POST['day'] < 1)
				{
					$error_content['date_error'] = true;
				}
				if($_POST['place'] == '' || $_POST['place'] == null){
					$error_content['place_error'] = true;
				}
				if($_POST['com_1'] == '' || $_POST['com_1'] == null || $_POST['com_2'] == '' || $_POST['com_2'] == null){
					$error_content['com_error'] = true;
				}
				if($_POST['number_people'] == '' || $_POST['number_people'] == null){
					$error_content['number_people_error'] = true;
				}
				if($_POST['department_1'] == $_POST['department_2']){
					$errot_content['department_error'] = true;
				}
				// エラーによる再入力要求をします
				if($error_content['date_error'] == true || 
					$error_content['place_error'] == true || 
					$error_content['com_error'] == true || 
					$error_content['number_people_error'] == true || 
					$error_content['department_error'] == true)
				{
					sirumoku_registration_error($error_content);
				}
				// エラーがなければ確認用のポップアップを起動します
				else{
					$_SESSION['regist_sirumoku'] = $_POST;
					$alert = sprintf('  <script type="text/javascript">
											if(window.confirm("登録内容をご確認ください\n\n日付: %s 年 %s 月 %s 日\n開催時間: %s \n開催場所: %s \n参加企業様: %s,%s \n定員: %s \n対象学科: %s,%s")){
												location.href = "manager.php?page_type=sirumoku";
											}
											else{
												history.back();
											}
										</script>',
										$_POST['year'],$_POST['month'],$_POST['day'],
										$_POST['time'],
										$_POST['place'],
										$_POST['com_1'],$_POST['com_2'],
										$_POST['number_people'],
										$_POST['department_1'],$_POST['department_2']
									);
					echo $alert;
				}
			}
			// シルモクデータの登録部分
			else if(!empty($_SESSION['regist_sirumoku']) && isset($_SESSION['regist_sirumoku'])){
				$year = $_SESSION['regist_sirumoku']['year'];
				if((int)$_SESSION['regist_sirumoku']['month'] < 10){
					$month = "0" . $_SESSION['regist_sirumoku']['month'];
				}
				else{
					$month = $_SESSION['regist_sirumoku']['month'];
				}
				if((int)$_SESSION['regist_sirumoku']['day'] < 10){
					$day = "0" . $_SESSION['regist_sirumoku']['day'];
				}
				else{
					$day = $_SESSION['regist_sirumoku']['day'];
				}
				$date = sprintf('%s-%s-%s',$year,$month,$day);
				$t = explode("~",$_SESSION['regist_sirumoku']['time']);
				$place = $_SESSION['regist_sirumoku']['place'];
				$number_people = $_SESSION['regist_sirumoku']['number_people'];
				$name_company = sprintf('%s,%s',$_SESSION['regist_sirumoku']['com_1'],$_SESSION['regist_sirumoku']['com_2']);
				$recommend = sprintf('[%s、%s]',$_SESSION['regist_sirumoku']['department_1'],$_SESSION['regist_sirumoku']['department_2']);
				
				if(!empty($_SESSION['update_id']) && isset($_SESSION['update_id'])){
					if($_SESSION['update_data'] == true){
						$sql = sprintf('UPDATE `sirumoku_data` SET `date`="%s", `start-time`="%s", `finish-time`="%s",`place`="%s",`number_people`="%s",`name_company`="%s",`recommend`="%s"
										WHERE id=%s',
										$date,
										$t[0],$t[1],
										$place,
										$number_people,
										$name_company,
										$recommend,
										$_SESSION['update_id']);
					}
				}
				else{
					$sql = sprintf('INSERT INTO `sirumoku_data`(`date`, `start-time`, `finish-time`, `place`, `number_people`, `name_company`, `recommend`)
									VALUES("%s","%s","%s","%s","%s","%s","%s")', 
									$date, 
									$t[0], $t[1], 
									$place, 
									$number_people, 
									$name_company, 
									$recommend);
				}
				echo $sql;
				mysqli_query($db,$sql) or die(mysqli_error($db));
				$_SESSION['regist_sirumoku'] = array();
				$_SESSION['update_id'] = "";
				$_SESSION['update_data'] = "";
				header('location:manager.php?page_type=sirumoku');
				exit();
			}
		}
		else if($_GET['page_type'] == 'lists'){
			$sql = 'SELECT `id`,`year`,`month`,`day`,`title` FROM `news` WHERE 1 ORDER BY `year`,`month`,`day`';
			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
			$news_lists = array();
			while($rec = mysqli_fetch_assoc($record)){
				$news_lists[] = $rec;
			}
			$sql = 'SELECT `id`,`date`,`name_company` FROM `sirumoku_data` WHERE 1 ORDER BY `date`';
			$record = mysqli_query($db, $sql) or die(mysqli_error($db));
			$sirumoku_lists = array();
			while($rec = mysqli_fetch_assoc($record)){
				$sirumoku_lists[] = $rec;
			}
		}
	}
	//新着情報追加・更新の際にエラーが発見されると以下が処理されます。
	function newEvent_registration_error($error_content, $id){
		$_SESSION['event'] = $_POST;
		$_SESSION['error'] = $error_content;
		if($id == "none"){
			$location = "location:manager.php?page_type=new_event&error=exist";
		}
		else{
			$location = sprintf('location:manager.php?page_type=new_event&error=exist&rewrite=%s',$id);
		}
		echo $location;
		header($location);
		exit();
	}
	//シルモク更新時のエラーの際は以下が処理されます
	function sirumoku_registration_error($error_content){
		$_SESSION['sirumoku'] = $_POST;
		$_SESSION['error'] = $error_content;
		if(!empty($_SESSION['update_id']) && isset($_SESSION['update_id'])){
			$location = sprintf('location:manager.php?page_type=sirumoku&rewrite=%s',$_SESSION['update_id']);
		}
		else{
			$location = 'location:manager.php?page_type=sirumoku&rewrite=exist';
		}
		header($location);
		exit();
	}
	function error_massage($str){
		echo "<h4 class='manager' style='width:70%;color:red'>";
		echo $str;
		echo "</h4>";
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
		<!-- 以下学内専用ページ部 -->
		<?php if(1): ?>
			<!-- 管理者画面トップページ -->
			<?php if(empty($_GET['page_type']) && !isset($_GET['page_type'])): ?>
				<?php login_checker(); ?>
				<?php if(!empty($_SESSION['cal_event']) && isset($_SESSION['cal_event'])){$_SESSION['cal_event'] = array();} ?>
				<?php if(!empty($_SESSION['error']) && isset($_SESSION['error'])){$_SESSION['error'] = array();} ?>
				<?php if(!empty($_SESSION['event']) && isset($_SESSION['event'])){$_SESSION['event'] = array();} ?>
				<?php if(!empty($_SESSION['sirumoku']) && isset($_SESSION['sirumoku'])){$_SESSION['sirumoku'] = array();} ?>
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
							<h2>新着情報登録ページ</h2>						
							<p>様々な新着情報の登録を行います</p>
						</div>
					</a>
					<a href="manager.php?page_type=lists" style='text-decoration:none;'>
						<div class='manager manager_contents'>
							<h2>各種データリストページ</h2>
							<p>各種データの閲覧を行います</p>
						</div>
					</a>
					<div style='width:30%;text-align:left;'>
						<a href="manager.php?page_type=log_out">ログアウト</a>
					</div>
				</div>
			<?php endif; ?>
			<!-- 管理者画面トップページはここまで -->

			<!-- 以下ページ切り替え部分 -->
			<?php if(!empty($_GET['page_type']) && isset($_GET['page_type'])): ?>
				
				<!-- シルモクデータ表示 -->
				<?php if($_GET['page_type'] == 'sirumoku'): ?>
					<?php login_checker(); ?>
					<?php 
						if(!empty($_GET['rewrite']) && isset($_GET['rewrite'])){
							$execute_rewrite = true;
							if($_GET['rewrite'] == "exist"){
								$rd = explode("~" , $_SESSION['sirumoku']['time']);
								$rewrite_data['start-time'] = $rd[0];
								$rewrite_data['finish-time'] = $rd[1];
								$rewrite_data['place'] = $_SESSION['sirumoku']['place'];
								$rewrite_data['name_company'] = $_SESSION['sirumoku']['com_1'] . "," . $_SESSION['sirumoku']['com_2'];
								$rewrite_data['number_people'] = $_SESSION['sirumoku']['number_people'];
								$rewrite_data['recommend'] = "[" . $_SESSION['sirumoku']['department_1'] . "、" . $_SESSION['sirumoku']['department_2'] . "]";
							}
						}
						else{
							$execute_rewrite = false;
						}
					?>
					<h2 style='width:70%' class='manager'>シルモク管理ページ</h2>
					<?php  
						if(!empty($_GET['rewrite']) && isset($_GET['rewrite'])){
							if(!empty($_SESSION['error']) && isset($_SESSION['error'])){
								if($_SESSION['error']['date_error'] == true){
									error_massage("日付が正しく入力されませんでした。再入力してください");
								}
								if($_SESSION['error']['place_error'] == true){
									error_massage("開催場所が正しく入力されませんでした");
								}
								if($_SESSION['error']['com_error'] == true){
									error_massage("企業名のどちらかが空欄になっていませんが？");
								}
								if($_SESSION['error']['number_people_error'] == true){
									error_massage("人数は正しく入力されていますか？");
								}
							}
						}
					?>
					<div class=''>
						<table width='70%' class='manager'>
							<tr>
								<th>開催日</th>
								<th>時間</th>
								<th>エントリー企業様</th>
								<th>申し込み総数</th>
							</tr>
							<?php foreach($sirumoku_data as $sirumoku_data): ?>
								<?php 
									if(!empty($_GET['rewrite']) && isset($_GET['rewrite'])){
										if($_GET['rewrite'] == $sirumoku_data['id']){
											$rewrite_data = $sirumoku_data;
											$_SESSION['update_id'] = $_GET['rewrite'];
											$_SESSION['update_data'] = true;
										}
									}
								?>
								<tr class='sirumoku_update' onclick='rewrite_sirumoku(<?php echo $sirumoku_data['id']; ?>)'>
									<td><?php echo $sirumoku_data['date']; ?></td>
									<td><?php echo $sirumoku_data['start-time']; ?>~<?php echo $sirumoku_data['finish-time']; ?></td>
									<td><?php echo $sirumoku_data['name_company']; ?></td>
									<td style='color:red;'>
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
								<th style='text-align:right;'>合計 </th>
								<th style='color:red;'><?php echo $sum['COUNT(id)']; ?></th>
							</tr>
						</table>
						<h3 style='width:70%' class='manager'>新規登録・更新はこちらから</h3>
						<div style='width:70%;' class="manager">
							<form class="" action="manager.php?page_type=sirumoku" method="post">
								<p>開催日</p>
								<?php  
									if($execute_rewrite == true){
										$date = explode("-", $rewrite_data['date']);
										$y = (int)$date[0];
										$m = (int)$date[1];
										$d = (int)$date[2];
									}
									else{
										$y = (int)date("Y");
										$m = (int)date("m");
										$d = (int)date("d");
									}
								?>
								<input type='number' name='year' value='<?php echo $y; ?>'>年
								<input type='number' name='month' min='1' max='12' value='<?php echo $m; ?>'>月
								<input type='number' name='day' min='1' max='31' value='<?php echo $d; ?>'>日
								<p>開催時間</p>
								<select class="" name="time">
									<?php 
										if($execute_rewrite == true){
											$str = sprintf('<option value="%s~%s">%s ~ %s</option>',$rewrite_data['start-time'],$rewrite_data['finish-time'],$rewrite_data['start-time'],$rewrite_data['finish-time']);
											echo $str;
										}
									?>
									<option value="09:00~10:30">9:00 ~ 10:30</option>
									<option value="10:40~12:10">10:40 ~ 12:10</option>
									<option value="13:10~14:40">13:10 ~ 14:40</option>
									<option value="14:50~16:20">14:50 ~ 16:20</option>
								  	<option value="16:30~18:00">16:30 ~ 18:00</option>
								</select>
								<p>開催場所</p>
								<input type="text" name="place" <?php if($execute_rewrite == true){echo "value='" . $rewrite_data['place'] . "'";} ?>>
								<p>企業名</p>
								<?php
									if($execute_rewrite == true){
										$c = explode(",", $rewrite_data['name_company']);
									}
								?>
								<input type="text" name="com_1" value="<?php if($execute_rewrite == true){echo $c[0];} ?>">
								<input type="text" name="com_2" value="<?php if($execute_rewrite == true){echo $c[1];} ?>">
								<p>定員</p>
								<input type="number" name="number_people" value="<?php if($execute_rewrite == true){echo $rewrite_data['number_people'];} ?>">
								<p>オススメの学科</p>
								<?php
									if($execute_rewrite == true){
										$rewrite_data['recommend'] = str_replace("[","",$rewrite_data['recommend']);
										$rewrite_data['recommend'] = str_replace("]","",$rewrite_data['recommend']);
										$d = explode("、" , $rewrite_data['recommend']);
									}
								?>
								<select class="" name="department_1">
									<?php
										if($execute_rewrite == true){
											$str = sprintf("<option value='%s'>%s</option>",$d[0],$d[0]);
											echo $str;
										}
									?>
									<option value="機械" >機械システム工学科</option>
									<option value="知能" >知能デザイン工学科</option>
									<option value="情報" >電子・情報工学科</option>
									<option value="生物" >生物工学科</option>
									<option value="環境" >環境工学科</option>
									<option value="医薬品" >医薬品工学科</option>
								</select>
								<select class="" name="department_2">
									<?php  
										if($execute_rewrite == true){
											$str = sprintf("<option value='%s'>%s</option>",$d[1],$d[1]);
											echo $str;
										}
									?>
									<option value="機械" >機械システム工学科</option>
									<option value="知能" >知能デザイン工学科</option>
									<option value="情報" >電子・情報工学科</option>
									<option value="生物" >生物工学科</option>
									<option value="環境" >環境工学科</option>
									<option value="医薬品" >医薬品工学科</option>
								</select>
								<br>
                				<input type="submit" class="manager_contents" value="編集">
              				</form>
              				<?php if($execute_rewrite == true): ?>
	                			<?php if($_GET['rewrite'] != "exist"): ?>
	                				<br>
	                				<br>
	                				<h3>シルモクのデータを削除する場合はこちらから</h3>
	                				<br>
    		            			<button calss='manager_contents' style='color:red;' onclick='sirumoku_delete(<?php echo $_GET['rewrite']; ?>)'>削除</button>
    		            		<?php endif; ?>
    		            	<?php endif; ?>
              			</div>
						<div style='width:30%;' class='manager'>
							<a href="manager.php"> <-管理者画面へ </a>
						</div>
					</div>
					<script type="text/javascript">
						function rewrite_sirumoku(id){
							var str = "manager.php?page_type=sirumoku&rewrite=" + id;
							document.location = str;
						}
						function sirumoku_delete(id){
							if(window.confirm("本当に削除しますか？\n削除した場合、復旧することができません")){
								var str = "manager.php?page_type=sirumoku&delete=" + id;
								location.href = str;
							}
							else{
								history.back();
							}
						}
					</script>
				<?php endif; ?>
				<!-- 以上シルモクデータ表示部分 -->

				<!-- 以下新着情報の更新画面 -->
				<?php if($_GET['page_type'] == 'new_event'): ?>
					<?php login_checker(); ?>
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
						<?php $news_error = true; ?>
					<?php endif; ?>
					<!-- 以上エラー部 -->

					<!-- 以下新着情報コンテンツ部 -->
					<div class='new_event'>
						<table class='arrange_rows'>
							<tr>
								<th width='50%'>
									<div class='manager'>
										<h2>新着情報更新ページ</h2>
										<form method='post' action='manager.php?page_type=new_event<?php if($rewrite == true){echo "&rewrite=" . $_GET["rewrite"];} ?>'>
											<dl>
												<dt>イベント名：</dt>
												<dd>
													<input 
														type='text' 
														name='title' 
														value='<?php  
																	if(!empty($news_error) && isset($news_error) || $rewrite == true) {
																		echo $_SESSION["event"]["title"];
																	}
																?>'>
												</dd>
												<dt>日付：</dt>
												<dd>
													<input type='number' name='year' value='<?php echo (int)date('Y'); ?>'>年
													<input type='number' name='month' min='1' max='12' value='<?php
																												if(!empty($news_error) && isset($news_error) || $rewrite == true){
																													echo $_SESSION["event"]["month"];
																												}
																												else{
																													echo (int)date("m"); 
																												}
																											?>'>月
													<input type='number' name='day' min='1' max='31' value='<?php
																												if(!empty($news_error) && isset($news_error) || $rewrite == true){
																													echo $_SESSION["event"]["day"];
																												}
																												else{
																													echo (int)date("d");
																												} 
																											?>'>日													
												</dd>
												<dt>詳細な時間など</dt>
												<dd>
													<input 
														type='text' 
														name='time_detail' 
														value='<?php  
																	if(!empty($news_error) && isset($news_error) || $rewrite == true){
																		echo $_SESSION["event"]["time_detail"];
																	}
																?>'
													>
												</dd>
												<dt>イベント詳細などコメント</dt>
												<dd>
													<textarea name='comment' cols='50' rows='5'><?php  
																if(!empty($news_error) && isset($news_error) || $rewrite == true){
																	echo $_SESSION["event"]["comment"];
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
														<option value='報告書'>報告書(カレンダーに表示されません)</option>
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
										<?php if($rewrite == true): ?>
											<h3>新着情報の削除はこちら</h3>
											<button onclick='delete_news()' style='color:red;'>削除</button>
											<script type="text/javascript">
												function delete_news(){
													if(window.confirm("イベントを削除します。よろしいですか？\n削除してしまった場合、復旧することができません\nカレンダーへの表示を止める場合は、イベント区分を報告書とすることで非表示にできます")){
														location.href = 'manager.php?page_type=delete_news&id=<?php echo $_GET['rewrite']; ?>';
													}
													else{
														history.back();
													}
												}
											</script>
										<?php endif; ?>
										<div style='width:30%;' class='manager'>
											<a href="manager.php"> <-管理者画面へ </a>
										</div>
									</div>
								</th>
								<!-- 以下最新の情報表示部 -->
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
											<dd><?php echo $recent_news['comment']; ?></dd>
											<dt>イベント区分：</dt>
											<dd><?php echo $recent_news['event_type']; ?></dd>
										</dl>
									</div>
								</th>
								<!-- 以上最新の情報表示部 -->
							</tr>
						</table>						
					</div>
					<!-- 以上新着情報コンテンツ部 -->
				<?php endif; ?>
				<!-- 以上新着情報更新部 -->

				<!-- 以下情報リストページ -->
				<?php if($_GET['page_type'] == 'lists'): ?>
					<div style='width:70%' class='manager'>
						<h2>シルモクデータ</h2>
						<table>
							<tr class='manager_contents'>
								<th>開催日</th>
								<th>参加企業</th>
							</tr>
							<?php foreach ($sirumoku_lists as $s_l): ?>
								<tr class='manager_contents' onclick='go_sirumoku(<?php echo $s_l["id"]; ?>)'>
									<td><?php echo $s_l['date']; ?></td>
									<td><?php echo $s_l['name_company']; ?></td>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
					<div style='width:70%' class='manager'>
						<h2>新着情報データ</h2>
						<table>
							<tr class='manager_contents'>
								<th>開催日</th>
								<th>イベント名</th>
							</tr>
							<?php foreach ($news_lists as $n_l): ?>
								<tr class='manager_contents' onclick='go_news(<?php echo $n_l["id"]; ?>)'>
									<td><?php echo $n_l['year'] . "-" . $n_l['month'] . "-" . $n_l['day']; ?></td>
									<td><?php echo $n_l['title']; ?></td>
								</tr>
							<?php endforeach ?>
						</table>
					</div>
					<script type="text/javascript">
						function go_sirumoku(id){
							document.location = "manager.php?page_type=sirumoku&rewrite=" + id;
						}
						function go_news(id){
							document.location = "manager.php?page_type=new_event&rewrite=" + id;
						}
					</script>
					<div style='width:30%;' class='manager'>
						<a href="manager.php"> <-管理者画面へ </a>
					</div>
				<?php endif; ?>
				<!-- 以上情報リストページ -->

				<!-- 以下ログイン部 -->
				<?php if($_GET['page_type'] == "log_in"): ?>
					<div style='width:70%' class='manager'>
						<span style='font-size:200%;'>ユーザログインページ</span>
						<p>以下の項目を入力の上、ログインボタンを押してください</p>
						<form method='post' action='manager.php'>
							<dl>
								<dt>ユーザネーム</dt>
								<dd><input type='text' name='user_name'></dd>
								<dt>パスワード</dt>
								<dd><input type='password' name='pass'></dd>
								<input type='submit' value='ログイン'>
							</dl>
						</form>
					</div>
				<?php endif; ?>
				<!-- 以上ログイン部 -->

				<!-- 以下ログアウト部 -->
				<?php if($_GET['page_type'] == "log_out"): ?>
					<?php 
						login_checker();
						$_SESSION['manager_login'] = false;
						header('location:manager.php');
						exit();
					?>
				<?php endif; ?>
				<!-- 以上ログアウト部 -->

				<!-- 以下新着情報情報登録部 -->
				<?php if($_GET['page_type'] == "registration"): ?>
					<?php 
						$sql = sprintf("INSERT INTO `news`(`year`,`month`,`day`,`title`,`time_detail`,`comment`,`event_type`,`target`,`created`)
														VALUES('%s','%s','%s','%s','%s','%s','%s','%s',NOW())",
																		$_SESSION['event']['year'],$_SESSION['event']['month'],$_SESSION['event']['day'],
																		$_SESSION['event']['title'],$_SESSION['event']['time_detail'],$_SESSION['event']['comment'],
																		$_SESSION['event']['event_type'],$_SESSION['event']['target']);
						mysqli_query($db, $sql) or die(mysqli_error($db));
						$_SESSION['event'] = array();
						header('location: manager.php?page_type=new_event');
						exit();
					?>
				<?php endif; ?>
				<!-- 以上新着情報登録部 -->

				<!-- 以下新着情報更新部 -->
				<?php if($_GET['page_type'] == "update_news"): ?>
					<?php 
						$sql = sprintf("UPDATE `news` SET title='%s',year='%s',month='%s',day='%s',time_detail='%s',comment='%s',event_type='%s',target='%s' WHERE id='%s'",
							$_SESSION['event']['title'],
							$_SESSION['event']['year'],
							$_SESSION['event']['month'],
							$_SESSION['event']['day'],
							$_SESSION['event']['time_detail'],
							$_SESSION['event']['comment'],
							$_SESSION['event']['event_type'],
							$_SESSION['event']['target'],
							$_SESSION['event']['id']
							);
						mysqli_query($db,$sql) or die(mysqli_error($db));
						header("location:manager.php?page_type=lists");
						exit();
					?>
				<?php endif; ?>
				<!-- 以上新着情報更新部 -->

				<!-- 以下新着情報削除処理部 -->
				<?php if($_GET['page_type'] == "delete_news"): ?>
					<?php 
						$sql = sprintf("DELETE FROM `news` WHERE id=%s",$_GET['id']);
						mysqli_query($db,$sql) or die(mysqli_error($db));
						header('location:manager.php?page_type=lists');
						exit();
					?>
				<?php endif; ?>
				<!-- 以上新着情報削除処理部 -->
			<?php endif; ?>
			<!-- 学内専用ページ部 -->
		<?php else: ?>
			<h1 style='width:70%;' class='manager'>学外からのアクセスを制限しています。申し訳ありません</h1>
		<?php endif; ?>
		<?php require("footer.php"); ?>
	</body>
</html>
