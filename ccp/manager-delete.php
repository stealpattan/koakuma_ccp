<?php
  session_start();
  require('dbconnect.php');

  $id = $_REQUEST['id'];
  $sql = sprintf('DELETE FROM `sirumoku_data` WHERE id = "%d"', $id);
  mysqli_query($db, $sql) or die(mysqli_error($db));

  header('Location: manager.php?page_type=sirumoku');
  exit();
?>
