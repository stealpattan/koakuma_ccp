<?php
require('dbconnect.php');
$datas = array();
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form class="" action="calnedar.html" method="post">
      <dl class="">
        <dt><label for="">実施日</label></dt>
        <?php
        $sql = sprintf('SELECT * FROM `sirumoku_data` WHERE 1');
        $recordSet = mysqli_query($db, $sql) or die(mysqli_error($db));
        while($data = mysqli_fetch_assoc($recordSet)){
          $array[] = explode("-", $data['date']);
        }$dt = new DateTime();
        $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        $today = $dt->format('Y-m-d');
        ?>
        <select name="event_date">
          <?php
          $sql = sprintf('SELECT * FROM `sirumoku_data` WHERE 1');
          $recordSet = mysqli_query($db, $sql) or die(mysqli_error($db));
          while($data = mysqli_fetch_assoc($recordSet)){
            if(strtotime($data['date']) > strtotime($today)){
          ?>
          <option value="<?php htmlspecialchars($data['date']) ?>"><?php echo $data['date']; ?></option>
          <?php }} ?>
        </select>
        <dt><label for="">氏名</label></dt>
        <input type="text" name="student_name" value="">
        <dt><label for="">性別</label></dt>
        <select name="sex">
          <option value="0">男</option>
          <option value="1">女</option>
        </select>
        <dt><label for="">学科・専攻</label></dt>
        <select name="event_date">
          <?php
          $sql = sprintf('SELECT * FROM `departments` WHERE 1');
          $recordSet = mysqli_query($db, $sql) or die(mysqli_error($db));
          while($data = mysqli_fetch_assoc($recordSet)){
            if($data['department_id'] != 0){
          ?>
          <option value="<?php htmlspecialchars($data['department_name']); ?>"><?php echo $data['department_name']; ?></option>
          <?php }} ?>
        </select>
        <dt><label for="">学籍番号</label></dt>
        <input type="text" name="student_number" value="">

      </dl>
    </form>
  </body>
</html>
