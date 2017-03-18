<!DOCTYPE html>
<html>
 <head>
   <meta charset="utf-8">
   <titile>カレンダーにイベントを追加</titile>
 </head>
 <body>
<form action="sent.php" method="post">
  <dl>
    <dt><label for="event">イベント名<labal></dt>
    <input type="text" name="event">
    <dt><label for="year">年<labal></dt>
    <select name="year">
      <?php echo("s"); ?>
      <option value="未選択">選択してください</option>
      <?php
        $y=2016;
        for ($y=2016; $y<=2100; $y++){?>
        <option value="<?php echo $y;?>"><?php echo $y;?></option>
      <?php }?>
    </select>
    <dt><label for="month">月<labal></dt>
    <select name="month">
      <option value="未選択">選択してください</option>
      <?php
      for ($mo=1; $mo<=12;$mo++) {
      echo "<option value='{$mo}'>{$mo}</option>";
      }?>
    </select>
    <dt><label for="day">日<labal></dt>
    <select name="day">
      <option value="未選択">選択してください</option>
      <?php
      for ($da=1; $da<=31;$da++) {
      echo "<option value='{$da}'>{$da}</option>";
      }?>
    </select>
    <dt><label for="detail">イベント詳細<labal></dt>
    <textarea name="detail"></textarea>
  </dl>
   <input type="submit" value="登録" />
</form>
</body>
</html>
