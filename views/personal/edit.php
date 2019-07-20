<?php$this->setLayout('title','登録情報編集ページ') ?>
<div>
  <article>
  <?php if(isset($errors)){$this->render('errors',array('errors'=>$errors));}?>
  <form action="<?php echo $this->escape($base_url); ?>/diary/register" method="post">
    <label>名前(必須)</label>
    <input value="<?php if(isset($name)){ echo $this->escape($name);} ?>">
      <label>メール(必須)</label>
    <input>メールvalue="<?php if(isset($mail)){echo $this->escape($mail);} ?>""
    メールで使える記号は@-_.のみ。文字は半角英数字、@前に記号はNG
      <label>現在のパスワード(必須)</label>
    <input value="">
    <p>パスワードを変更する際は以下の欄に入力してください</p>
    <label>新しいパスワード</label>
    <input value="<?php if(isset($new_paswword)){ echo $this->escape($new_password);}else{echo '';}?>">
    <label>新しいパスワードを再入力してください</label>
    <input value="<?php if(isset($confirm_password)){ echo $this->escape($confirm_password); }else{echo '';}?>">
    <input type="hidden" value="<?php if(isset($token)){ echo $this->escape($token); }else{echo '';}?>">
    <button>登録する</button>
  </form>
</article>
  <a href="<?php echo $this->escape($base_url); ?>/personal">マイページへ戻る</a>
</div>
