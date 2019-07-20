<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charest="utf-8">
  <title><?php if(isset($title)){echo $this->escape($title);} ?>-'Emo-Diary'</title>
<link rel="stylesheet" href="">
<body>
  <header>
    <h1>EmoDiary</h1>
    <nav>
  <ul>
    <li>ようこそ<?php if(isset($user)){echo $this->escape($user['user_name']);} ?>さん</li>
    <li><a href="<?php echo $this->escape($base_url); ?>/personal">マイページ</a></li>
    <li><a href="<?php echo $this->escape($base_url); ?>/service/emotions">感情の種類</a></li>
    <li><a href="<?php echo $this->escape($base_url); ?>/service/index">使い方</a></li>
    <?php if($session->isAuthenticated()): ?>
    <li><a href="<?php echo $this->escape($base_url); ?>/account/signin">サインイン</a></li>
  <?php else: ?>
    <li><a href="<?php echo $this->escape($base_url); ?>/account/signout">サインアウト</a></li>
  <?php endif; ?>
  </ul>

  <nav>
  </header>
  <div>
    <?php echo $content; ?>
  </div>
  <footer>
  </footer>
</body>
