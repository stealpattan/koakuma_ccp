<!DOCTYPE html>
<?php
  require ('../dbconnect.php');

  $sql = sprintf('INSERT INTO calendar_datas SET event="%s", year=%d, month=%d, day=%d, detail="%s"',
  mysqli_real_escape_string($db, $_POST['event']),
  mysqli_real_escape_string($db, $_POST['year']),
  mysqli_real_escape_string($db, $_POST['month']),
  mysqli_real_escape_string($db, $_POST['day']),
  mysqli_real_escape_string($db, $_POST['detail'])
  );
  mysqli_query($db,$sql) or die(mysqli_error($db));
 ?>
<html>
<head>
  <meta charset="utf-8">
  <title>Carender</title>
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
  <?php

  ?>
  <p>登録が完了しました。</p>
  <ul>
    <li><a href="index.php">一覧に戻る</a></li>
    <li><a href="index2.php">登録画面に戻る</a></li>
  </ul>


</body>
</html>
