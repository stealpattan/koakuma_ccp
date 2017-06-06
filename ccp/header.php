<header>
  <img class="logo" width='100%' src="./assets/img/logo/tpu_logo_set.svg" alt="TPUのロゴ"/>
  <!-- ナビメニュー -->
  <div class="nav-menu">
    <ul>
      <a class="<?php if(basename($_SERVER['PHP_SELF']) == 'home.php'){echo 'selected_tab';}else{echo "unselected_tab";} ?>" href="home.php"><div class='header_style' id="home">ホーム</div></a>
      <a class="<?php if(basename($_SERVER['PHP_SELF']) == 'info_career.php'){echo 'selected_tab';}else{echo "unselected_tab";} ?>" href="info_career.php"><div class='header_style' id="info-career">就職情報</div></a>
      <a class="<?php if(basename($_SERVER['PHP_SELF']) == 'recruitment.php'){echo 'selected_tab';}else{echo "unselected_tab";} ?>" href="recruitment.php"><div class='header_style' id="intern">求人情報</div></a>
    </ul>
  </div>
</header>
