<?php $this->setTitle('title','新規登録入力ページ') ?>

<div>
  <div  class="topImage">
  <img src="<?php echo $this->escape($stage_url); ?>images/noteImage3.jpg">
  </div>
  <article>
    <?php if(isset($errors)){echo $this->render('errors',array('errors'=>$errors));}?>
    <!-- 入力箇所 -->
    <form action="<?php echo $this->escape($base_url); ?>/account/signup/register" method="post">
      <label>名前</label>
       <p><input name="name" value = "<?php echo $this->escape($name); ?>"></p>
       <label>メール</label>
      <p><input name="mail" value="<?php echo $this->escape($mail); ?>"></p>
        <p>メールで使える記号は@-_.のみ。文字は半角英数字、@の直前に記号はつかえません</p>
      <label>パスワード</label>
      <p><input type="password" name="password" value="<?php echo $this->escape($password); ?>"></p>
      <label>パスワード確認</label>
      <p><input type="password" name="confirm_password" value="<?php echo $this->escape($confirm_password); ?>"></p>
      <input type="hidden" name="csrf_token" value="<?php echo $this->escape($token); ?>">
      <button type="submit">登録する</button>
    </form>
  </article>
  <p><a href="<?php echo $this->escape($base_url); ?>/account/signin">サインインページへ</a></p>
</div>
