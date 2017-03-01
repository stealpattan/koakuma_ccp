<?php
//connect to database
require('../dbconnect.php');

//$_POSTがからじゃない時に実行
if (!empty($_POST)) {
  $sql=sprintf('INSERT INTO company_datas SET company_name="%s", indust_type="%s", address="%s", url_list="%s"',
    mysqli_real_escape_string($db, $_POST['company_name']),
    mysqli_real_escape_string($db, $_POST['indust_type']),
    mysqli_real_escape_string($db, $_POST['address']),
    mysqli_real_escape_string($db, $_POST['url_list']));
  mysqli_query($db, $sql) or die(mysqli_error($db));
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>企業情報の登録</title>
  </head>
  <body>
    <p>
      登録完了
    </p>
    <ul>
      <li><a href="recruitment-input.php">登録画面へ戻る</a></li>
      <li><a href="recruitment.php">一覧ページへ戻る</a></li>
    </ul>
  </body>
</html>
