<?php$this->setLayout('title','新規登録入力ページ') ?>

<div>
  <article>
    <?php if(isset($errors)){$this->render('errors',array('errors'=>$errors));}?>
    <!-- 入力箇所 -->
    <form action="<?php echo $this->escape($base_url); ?>/acount/signup/register" method="post">
      <label>名前</label>
       <input value="<?php echo $this->escape($name); ?>">
       <label>メール</label>
      <input value="<?php echo $this->escape($mail); ?>">
      <label>パスワード</label>
      <input value="<?php echo $this->escape($password); ?>">
      <label>パスワード確認</label>
      <input value="<?php echo $this->escape($confirm_password); ?>">
      <input type="hidden" name="csrf_token" value="<?php echo $this->escape($token); ?>">
      <button type="submit">登録する</button>
    </form>
  </article>
  <a href="<?php echo $this->escape($base_url); ?>/account/signin">サインインページへ移動する</a>
</div>
