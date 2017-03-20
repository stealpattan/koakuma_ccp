<?php
  function mysqlRes($db, $value){
    return mysqli_real_escape_string($db, $value);
  }

  function sum_pref($a, $b, $c, $d, $e, $f, $g, $h){
    return $a + $b + $c + $d + $e + $f + $g + $h;
  }
  // 県内と県外の求人の合計
  function sum_num_recruit($a, $b){
    return $a + $b;
  }
  // 学科毎の内定者の合計
  function sum_num_private_decision($a, $b, $c, $d, $e){
    return $a + $b + $c + $d + $e;
  }

  function ip_tracer(){
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip = explode(".", $ip);
    if($ip[0] == 133){
      if($ip[1] == 55){
        return true;
      }
      else{
        return false;
      }
    }
    else{
      return false;
    }
  }

  function log_in($user_name,$pass){
    require('dbconnect.php');
    $sql = sprintf('SELECT * FROM `managers` WHERE 1');
    $record = mysqli_query($db,$sql) or die(mysqli_error($db));
    $manager_data = array();
    while($rec = mysqli_fetch_assoc($record)){
      $manager_data[] = $rec;
    }
    foreach($manager_data as $m){
      if($m['user_name'] == $user_name){
        if($m['password'] == $pass){
          return true;
        }
        else{
          return false;
        }
      }
    }
  }

  function login_checker(){
    if(empty($_SESSION['manager_login']) && !isset($_SESSION['manager_login'])){
      header('location:manager.php?page_type=log_in');
      exit();
    }
    else{
      if($_SESSION['manager_login'] != true){
        header('location:manager.php?page_type=log_in');
        exit();
      }
    } 
  }
?>
