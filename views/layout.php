<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title><?php if(isset($title)){echo $this->escape($title);} ?>-Emo-Diary</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link rel="stylesheet"  href=" <?php echo $this->escape($stage_url);?>css/normalize.css">
  <link rel="stylesheet" href=" <?php  echo $this->escape($stage_url);?>css/style.css">
  <script type="text/javascript" src="<?php echo $this->escape($stage_url); ?>js/menu.js"></script>
</head>
<body>
  <div id="page">
    <header id="header">
      <div  class="title"><h1>EmoDiary</h1>
      <a href="<?php echo $base_url; ?>/"><img class="titleImage" src="<?php echo $this->escape($stage_url); ?>images/note2.jpeg"></a>
    </div>
      <nav class="globalNavi">
        <ul>
          <li><?php if(isset($user)):?>
            <a href="<?php echo $this->escape($base_url); ?>/personal">
              <?php echo $this->escape($user['name']).'さん'; ?></a></li>
            <?php else: ?>
              <?php echo 'ゲストさん'; ?>
            <?php endif; ?>
            <?php if(!$session->isAuthenticated()): ?>
              <li><a href="<?php echo $this->escape($base_url); ?>/account/signin">サインイン</a></li>
            <?php else: ?>
              <li><a href="<?php echo $this->escape($base_url); ?>/account/signout">サインアウト</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </header>

      <div id="hamburgerBtn">
      <img src="<?php echo $this->escape($stage_url); ?>images/btn1.jpg">
      </div>

      <div id="sideMenu" class="">
        <nav>
          <ul>
            <li>ようこそ<?php if(isset($user)){echo $this->escape($user['name']).'さん';} ?></li>
            <li><a href="<?php echo $this->escape($base_url); ?>/personal">マイページ</a></li>
            <li><a href="<?php echo $this->escape($base_url); ?>/service/emotions">感情の種類</a></li>
            <li><a href="<?php echo $this->escape($base_url); ?>/service/index">使い方</a></li>
            <?php if(!$session->isAuthenticated()): ?>
              <li><a href="<?php echo $this->escape($base_url); ?>/account/signin">サインイン</a></li>
            <?php else: ?>
              <li><a href="<?php echo $this->escape($base_url); ?>/account/signout">サインアウト</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
      <div id="surface" class=""></div>
      <div  id="main">
        <?php echo $content; ?>
      </div>
      <footer id ="footer">
        <p id="copyright"><small>Copyright&copy; 2019 @EmoDiary All Rights Reserved.</small></p>
      </footer>

  </div>
  </body>
