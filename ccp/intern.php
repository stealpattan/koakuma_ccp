<?php
	session_start();
	require('dbconnect.php');
	require('function.php');
	date_default_timezone_set('Asia/Tokyo');
	//職種で呼ばれた場合は、A,B,...がくる
	//学科で呼ばれた場合は、機械だとか知能だとかがくる
	if(!empty($_GET) && isset($_GET)){
		if(!empty($_GET['business_type']) && isset($_GET['business_type'])){
			$job_kind = $_GET['business_type'];
			$sql = sprintf('SELECT * FROM `employee` WHERE `job_kind` = "%s"', $job_kind);
			$record = mysqli_query($db,$sql) or die(mysqli_error($db));
			$data = array();
			while($rec = mysqli_fetch_assoc($record)){
				$data[] = $rec;
			}
		}
		else if(!empty($_GET['depertment']) && isset($_GET['depertment'])){
			$depertment_type = $_GET['depertment'];
			$sql = sprintf('SELECT * FROM `employee` WHERE 1');
			$record = mysqli_query($db,$sql) or die(mysqli_error());
			$data = array();
			while($rec = mysqli_fetch_assoc($record)){
				$data[] = $rec;
			}
		}
	}
	else{
		$sql = sprintf('SELECT * FROM `employee` WHERE 1');
		$record = mysqli_query($db,$sql) or die(mysqli_error($db));
		$data = array();
		while($rec = mysqli_fetch_assoc($record)){
			$data[] = $rec;
		}
	}
	//データベースに登録された就職者の人数についてのカラム（String型）から人数を計算するメソッド
	function count_employer($str){
		$depertment_data = explode(":", $str);
		$each_employer_num = array();
		$num_of_employer = 0;
		//学科分け
		for($i=0;$i<count($depertment_data);$i++){
			$each_employer_num[$i] = explode("_", $depertment_data[$i]);
		}
		//就職者の人数部分を取り出して足し合わせ
		for($j=0;$j<count($each_employer_num);$j++){
			$num_of_employer += $each_employer_num[$j][1];
		}
		return $num_of_employer;
	}
	//学科が送られてきたらこれが呼ばれます
	function count_depertment_employer($str, $dep){
		$depertment_data = explode(":", $str);
		$each_employer_num = array();
		$num_of_employer = 0;
		for($i=0;$i<count($depertment_data);$i++){
			$each_employer_num[$i] = explode("_" , $depertment_data[$i]);
		}
		for($j=0;$j<count($each_employer_num);$j++){
			if($each_employer_num[$j][0] == $dep){
				//echo "キタキター";
				if($each_employer_num[$j][1] >= 1){
					//echo "ありました";
					return $each_employer_num[$j][1];
				}
			}
		}
	}
 ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>就職先データ</title>
		<link rel="shortcut icon" href="./assets/img/logo/tpu_logo.png">
		<link rel="stylesheet" href="./assets/css/reset.css">
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/common.css">
		<link rel="stylesheet" href="./assets/css/intern.css">
	</head>
	<body>
		<?php include_once("analyticstracking.php") ?>
		<?php require('header.php'); ?>
		<div class="info-career" style="width: 70%; margin: 0 auto;">
      <div class="breadcrumbs">
        <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="info-career.php">就職情報</a></li>
					<li class="here">就職者情報</li>
        </ul>
      </div>
    </div>
		<?php if(ip_tracer() == true): ?>
		<!-- コンテンツ部分 -->
		<div class="contents">
			<div class="past-info">

			</div>
			<div class="introduce">
				<h2>過去5年分の就職者情報</h2>
				<p>
					下記には、
					<?php
						$m = date('m');
						if($m < 4){
							echo date('Y')-1;
						}
						else if($m >= 4 && $m <= 12){
							echo date('Y');
						}
					?>
					年度より過去5年間のそれぞれの企業への就職者人数を表示しています。
				</p>
			</div>
			<div　id = "table-01">
				<!-- 参考サイト：http://bashalog.c-brains.jp/08/06/13-165130.php -->
				<table id="table-02">
					<tr>
						<th>社名</th>
						<?php
							if(empty($_GET['business_type']) && !isset($_GET['business_type'])){
								echo "<th>職種</th>";
							}
						?>
						<th>
							<?php
								if(!empty($_GET['depertment']) && isset($_GET['depertment'])){
		 							echo $_GET['depertment'];
		 							echo "からの過去５年間の";
		 						}
							?>
							就職者人数
						</th>
					</tr>
					<?php foreach($data as $data): ?>
						<?php
							if(!empty($depertment_type) && isset($depertment_type)){
								$employer_num = count_depertment_employer($data['5_ago'], $depertment_type) +
									count_depertment_employer($data['4_ago'], $depertment_type) +
									count_depertment_employer($data['3_ago'], $depertment_type) +
									count_depertment_employer($data['2_ago'], $depertment_type) +
									count_depertment_employer($data['last_year'], $depertment_type);
								if($employer_num >= 1){
									echo "<tr>";
										echo "<td>";
											echo $data['company_name'];
										echo "</td>";
										echo "<td>";
											echo $data['job_kind'];
										echo "</td>";
										echo "<td>";
											echo $employer_num;
										echo "</td>";
									echo "</tr>";
								}
							}
							else{
								echo "<tr>";
									echo "<td>";
										echo $data['company_name'];
									echo "</td>";
									if(empty($_GET['business_type']) && !isset($_GET['business_type'])){
										echo "<td>";
											echo $data['job_kind'];
										echo "</td>";
									}
									echo "<td>";
										$num = count_employer($data['5_ago']) +
											count_employer($data['4_ago']) +
											count_employer($data['3_ago']) +
											count_employer($data['2_ago']) +
											count_employer($data['last_year']);
										echo $num;
									echo "</td>";
								echo "</tr>";
							}
						endforeach; ?>
				</table>
			</div>
		</div>
		<?php else: ?>
		<div style='width:70%;margin:0 auto;'>
			<h1>学外からのアクセスを制限しています。申し訳有りません</h1>
		</div>
		<?php endif; ?>
		<?php include('footer.php'); ?>
	</body>
</html>
