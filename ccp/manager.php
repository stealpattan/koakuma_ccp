<?php
	session_start();
	date_default_timezone_set('Asia/Tokyo');
	require('dbconnect.php');

	$error_array = array();
	$error_array['title_error'] = false;
	$error_array['date_error'] = false;
	$error_array['detail_error'] = false;
	$errors = array();

	if(!empty($_POST) && $_REQUEST['page_type'] == 'sirumoku'){
		if($_POST['date'] == '' || $_POST['date'] == null){
			$errors['data'] = 'blank';
		}
		if($_POST['place'] == '' || $_POST['place'] == null){
			$errors['place'] = 'blank';
		}
		if($_POST['com_1'] == '' || $_POST['com_1'] == null){
			$errors['com_1'] = 'blank';
		}
		if($_POST['com_2'] == '' || $_POST['com_2'] == null){
			$errors['com_2'] = 'blank';
		}
		if($_POST['number_people'] == '' || $_POST['number_people'] == null){
			$errors['number_people'] = 'blank';
		}
		else{
			$_SESSION['sirumoku'] = $_POST;
			$company_name = $_POST['com_1'].','.$_POST['com_2'];
			$recommend = '['.$_POST['department_1'].'、'.$_POST['department_2'].'推薦]';
			$time = explode("~", $_POST['time']);
			$start_time = $time[0];
			$finish_time = $time[1];
			//DBに会員情報を登録
			$sql_entry = sprintf('INSERT INTO `sirumoku_data` SET `date` = "%s", `start-time` = "%s", `finish-time` = "%s", `place` = "%s", `number_people` = "%d", `recommend` = "%s", `name_company` = "%s"',
			mysqli_real_escape_string($db, $_POST['date']),
			mysqli_real_escape_string($db, $start_time),
			mysqli_real_escape_string($db, $finish_time),
			mysqli_real_escape_string($db, $_POST['place']),
			mysqli_real_escape_string($db, $_POST['number_people']),
			mysqli_real_escape_string($db, $recommend),
			mysqli_real_escape_string($db, $company_name)
			);
			mysqli_query($db, $sql_entry) or die(mysqli_error());
			header('location: manager.php?page_type=sirumoku');
			exit();
		}
	}

	if(!empty($_POST) && $_REQUEST['page_type'] == 'regestration'){
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
												</script>',$_POST['event_title'],$_POST['month'],
																		$_POST['day'],$_POST['time_detail'],
																		$_POST['comment'],$_POST['event_type'],
																		$_POST['target']);
			echo $alert;
		}
	}

	$sql = sprintf("SELECT * FROM `news` WHERE 1 ORDER BY created DESC LIMIT 1");
	$rec = mysqli_query($db, $sql) or die(mysqli_error($db));
	$recent_news = mysqli_fetch_assoc($rec);

	$date_y = date('Y');
	$date_m = date('m');
	$today = date('Y-m-d');

	if($date_m >= '03' && $date_m < '09'){
		$school_season = '前期';
		$sql_date = sprintf('SELECT * FROM `sirumoku_data` WHERE sirumoku_data.date >= "%d-03-01" AND sirumoku_data.date < "%d-09-01"', $date_y, $date_y);
		$record_date = mysqli_query($db, $sql_date);
		while($table = mysqli_fetch_assoc($record_date)){
			$datas[] = $table;
			foreach ($datas as $key => $value) {
				$date[$key] = $value['date'];
			}
			// array_multisortで'id'の列を昇順に並び替える
			array_multisort($date, SORT_ASC, $datas);
		}
	}else{
		$school_season = '後期';
		if($date_m >= '09'){
			$sql_date = sprintf('SELECT * FROM `sirumoku_data` WHERE sirumoku_data.date >= "%d-09-01" AND sirumoku_data.date < "%d-03-01"', $date_y, $date_y+1);
			$record_date = mysqli_query($db, $sql_date);
		}elseif($date_m < '09'){
			$sql_date = sprintf('SELECT * FROM `sirumoku_data` WHERE sirumoku_data.date >= "%d-09-01" AND sirumoku_data.date < "%d-03-01"', $date_y-1, $date_y);
			$record_date = mysqli_query($db, $sql_date);
			$date_y = $date_y - 1;
		}
		echo $date_y;
		while($table = mysqli_fetch_assoc($record_date)){
			$datas[] = $table;
			foreach ($datas as $key => $value) {
				$date[$key] = $value['date'];
			}
			// array_multisortで'id'の列を昇順に並び替える
			array_multisort($date, SORT_ASC, $datas);
		}
	}
	$deadline=date('Y-m-d', strtotime("+3 day"));

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
		<link rel="stylesheet" href="css/sirumoku.css">
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
			<?php if($_GET['page_type'] == 'sirumoku'): ?>
				<!-- シルモクのデータを表示する場所 -->
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
				<div class="new_event">
					<div class="sirumoku_datas">
						<div class="school_season">
							<p><?php echo  $date_y.'年度'.$school_season.'分シルモクデータ'; ?></p>
						</div>
	          <table class="table table-bordered table-striped trhover" style="width:100%;">
	            <tr class="s_data_list">
	              <th class="s_data_day">開催日</th>
	              <th class="s_data_time">時間帯</th>
	              <th class="s_data_place">開催場所</th>
								<th class="s_data_place">定員</th>
								<th class="s_data_place">申込人数</th>
	              <th class="s_data_name">企業名</th>
								<th class="s_data_name">編集・削除</th>
	            </tr>
	            <?php
	            foreach($datas as $data):
	              //開催日
	              $array = explode("-", $data['date']);
	              $str1 = str_split($array[1]);
	              $str2 = str_split($array[2]);
	              if($str1[0] == 0){
	                $str1[0] = '';
	              }
	              if($str2[0] == 0){
	                $str2[0] = '';
	              }
	              $str1=$str1[0].$str1[1];
	              $str2=$str2[0].$str2[1];
	              $date_time=$array[0]."/".$str1."/".$str2;
	              //開始時間
	              $table_st_data=$data['start-time'];
	              $array = explode(":", $table_st_data);
	              $data_start=$array[0].":".$array[1];
	              //終了時間
	              $table_ft_data=$data['finish-time'];
	              $array = explode(":", $table_ft_data);
	              $data_finish=$array[0].":".$array[1];
	              //会社名
	              $table_company_data=$data['name_company'];
	              $array = explode(",", $table_company_data);
	              //sirumoku_entryの各受付数を取得
	              $sql_entry=sprintf('SELECT COUNT(`event_date`) AS cnt FROM `sirumoku_entry` WHERE event_date = "%s"', $data['date']);
	              $record_entry=mysqli_query($db,$sql_entry);
	              $entry_number = mysqli_fetch_assoc($record_entry);
	              $cnt = $entry_number["cnt"];
	              $remain = $data['number_people'] - $cnt;
	              $errors['entry'] = '';
	              if($data['date'] < $deadline){
	                $errors['entry'] = 'deadline';
	              }elseif($cnt == $data['number_people']){
	                $errors['entry'] = 'over';
	              }elseif($remain <= 5){
	                $errors['entry'] = 'warning';
	              }
	              ?>
	              <tr>
	                <?php if(!empty($errors['entry'])): ?>
	                  <th class="table_data_date"><p style="padding-top:8px;"><?php echo htmlspecialchars($date_time); ?></p></th>
	                  <th class="table_data_time"><p style="padding-top:8px;"><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></p></th>
	                  <th class="table_data_place"><p style="padding-top:8px;"><?php echo htmlspecialchars($data['place']) ?></p></th>
										<th class="table_data_place"><p style="padding-top:8px;"><?php echo htmlspecialchars($data['number_people']) ?></p></th>
										<th class="table_data_place"><p style="padding-top:8px;"><?php echo htmlspecialchars($cnt) ?></p></th>
	                <?php else: ?>
	                  <th class="table_data_date"><p><?php echo htmlspecialchars($date_time); ?></p></th>
	                  <th class="table_data_time"><p><?php echo htmlspecialchars($data_start.' ~ '.$data_finish); ?></p></th>
	                  <th class="table_data_place"><p><?php echo htmlspecialchars($data['place']) ?></p></th>
										<th class="table_data_place"><p><?php echo htmlspecialchars($data['number_people']) ?></p></th>
										<th class="table_data_place"><p><?php echo htmlspecialchars($cnt) ?></p></th>
	                <?php endif; ?>
	                <th>
	                  <p style="margin:0; font-size:10px;"><?php echo  htmlspecialchars($data['recommend']); ?></p>
	                  <?php echo htmlspecialchars($array[0])."<br>".htmlspecialchars($array[1]); ?>
	                  <?php if (isset($errors['entry']) && $errors['entry'] == 'deadline' ) : ?>
	                    <p class="error" style="color: red; font-size: 10px; margin: 0;">受付を終了しました</p>
	                  <?php elseif (isset($errors['entry']) && $errors['entry'] == 'over' ) : ?>
	                    <p class="error" style="color: red; font-size: 10px; margin: 0;">定員に達しました</p>
	                  <?php elseif (isset($errors['entry']) && $errors['entry'] == 'warning') : ?>
	                    <p class="error" style="color: red; font-size: 10px; margin: 0;">残り<?php echo $remain; ?>名で定員に達します</p>
	                  <?php endif; ?>
	                </th>
									<?php if(!empty($errors['entry'])): ?>
										<th class="table_data_place"><p style="padding-top:8px;">[&nbsp;<a href="manager-update.php?id=<?php echo $data['id'] ?>">編集</a>・<a href="manager-delete.php?id=<?php echo $data['id'] ?>">削除</a>&nbsp;]</p></th>
	                <?php else: ?>
	                  <th class="table_data_place"><p>[&nbsp;<a href="manager-update.php?id=<?php echo $data['id'] ?>">編集</a>・<a href="manager-delete.php?id=<?php echo $data['id'] ?>">削除</a>&nbsp;]</p></th>
	                <?php endif; ?>

	              </tr>
	            <?php endforeach; ?>
	          </table>
						<div class="sirumoku_form" style="margin-top:20px;">
							<form class="" action="" method="post">
								<p>開催日</p>
								<input type="text" name="date" value="" placeholder="<?php echo $today; ?>">
								<p>開催時間</p>
								<select class="" name="time">
									<option value="09:00~10:30">9:00 ~ 10:30</option>
									<option value="10:40~12:10">10:40 ~ 12:10</option>
									<option value="13:10~14:40">13:10 ~ 14:40</option>
									 <option value="14:50~16:20">14:50 ~ 16:20</option>
									 <option value="16:30~18:00">16:30 ~ 18:00</option>
								</select>
								<p開催場所></p>
								<input type="text" name="place" value="" placeholder="A307">
								<p>企業名</p>

								<input type="text" name="com_1" value="" placeholder="朝日印刷株式会社">
								<input type="text" name="com_2" value="" placeholder="三光合成株式会社">
								<p>定員</p>
								<input type="text" name="number_people" value="" placeholder="30">
								<p>オススメの学科</p>
								<select class="" name="department_1">
									<option value="機械">機械システム工学科</option>
									<option value="知能">知能デザイン工学科</option>
									<option value="情報">電子・情報工学科</option>
									<option value="生物">生物工学科</option>
									<option value="環境">環境工学科</option>
									<option value="医薬品">医薬品工学科</option>
								</select>
								<select class="" name="department_2">
									<option value="機械">機械システム工学科</option>
									<option value="知能">知能デザイン工学科</option>
									<option value="情報">電子・情報工学科</option>
									<option value="生物">生物工学科</option>
									<option value="環境">環境工学科</option>
									<option value="医薬品">医薬品工学科</option>
								</select><br>
								<input type="submit" name="" value="登録">
							</form>
						</div>
						<div style='width:30%;' class='manager'>
							<a href="manager.php"> <-管理者画面へ </a>
						</div>
	        </div>
				</div>
			<?php endif; ?>
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
