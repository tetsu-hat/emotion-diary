<?php $this->setTitle('title','サインイン') ?>
<div>
  <div  class="topImage">
  <img src="<?php echo $this->escape($stage_url); ?>images/noteImage3.jpg">
  </div>
  <article>
    <!-- 誘導するような箇所 -->
    <p>日記を残しませんか？<br>
    会社や学校、家族や友人とシチュエーションを分けて、出来事に対する感情も残しましょう。</p>

  </article>

  <article>
    <?php if(isset($errors)){echo $this->render('errors',array('errors'=>$errors));}?>
    <!-- 入力する箇所 -->
    <form action="<?php echo $this->escape($base_url); ?>/account/authenticate" method="post">

      <label>メール</label>
      <p><input name="mail" value="<?php echo $this->escape($mail); ?>"></p>
      <p>メールで使える記号は@-_.のみ。文字は半角英数字、@の直前に記号はつかえません</p>
      <label>パスワード</label>
      <p><input type="password" name="password" value="<?php echo $this->escape($password); ?>"></p>
      <input type="hidden" name="csrf_token" value="<?php echo $this->escape($token); ?>" >
      <button type-"submit">サインイン</button>
    </form>
  </article>
  <p><a href="<?php echo $this->escape($base_url); ?>/account/signup">新規登録ページへ</a></p>
</div>
