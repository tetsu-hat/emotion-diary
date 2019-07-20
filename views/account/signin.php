<?php$this->setLayout('title','サインイン入力ページ') ?>

<div>
  <article>
    <!-- 誘導するような箇所 -->
    <p>登録して日記を残しませんか？<br>
    会社や学校、家族や友人とシチュエーションを分けて、出来事に対する感情も残しましょう。<p>

  </article>

  <article>
    <?php if(isset($errors)){$this->render('errors',array('errors'=>$errors));}?>
    <!-- 入力する箇所 -->
    <form action="<?php echo $this->escape($base_url); ?>/account/authenticate" method="post">
      <label>名前</label>
      <input value="<?php echo $this->escape($name); ?>">
      <label>メール</label>
      <input value="<?php echo $this->escape($mail); ?>">
      メールで使える記号は@-_.のみ。文字は半角英数字、@前に記号はNG
      <label>パスワード</label>
      <input name="password" value="<?php echo $this->escape($password); ?>">
      <input type="hidden" name="csrf_token" value="<?php echo $this->escape($token); ?>" >
      <button>ログインする</button>
    </form>
  </article>
  <a href="<?php $this->escape($base_url); ?>/account/signup">新規登録ページへ</a>
</div>
