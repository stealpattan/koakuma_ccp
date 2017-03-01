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
?>
