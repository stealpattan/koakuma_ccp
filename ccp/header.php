<header>
  <img class="nav-logo" width='100%' src="./assets/img/logo/tpu_logo_set.svg" alt="TPUのロゴ"/>
  <!-- ナビメニュー -->
  <div class="nav-menu">
    <ul>
      <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'home.php'){echo 'selected_tab';}else{echo "unselected_tab";} ?>"><a href="home.php">ホーム</a></li>
      <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'info_career.php'){echo 'selected_tab';}else{echo "unselected_tab";} ?>"><a href="info_career.php">就職情報</a></li>
      <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'recruitment.php'){echo 'selected_tab';}else{echo "unselected_tab";} ?>"><a href="recruitment.php">求人情報</a></li>
    </ul>
  </div>
</header>
