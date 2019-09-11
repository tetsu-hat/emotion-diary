<?php $this->setTitle('title','登録情報編集ページ') ?>
<div>
  <div class="topImage">
    <img src="<?php echo $this->escape($stage_url); ?>images/noteImage1.jpg">
  </div>
  <article>
    <?php if(isset($errors)){echo $this->render('errors',array('errors'=>$errors));}?>
    <form action="<?php echo $this->escape($base_url); ?>/personal/register" method="post">
      <label>名前<text class="attention">(必須)</text></label>
      <p><input name="name" value="<?php if(isset($name)){ echo $this->escape($name);} ?>"></p>
      <label>メール<text class="attention">(必須)</text></label>
      <p><input name="mail" value="<?php if(isset($mail)){echo $this->escape($mail);} ?>"></p>
      <p><text class="attention">*メールで使える記号は@-_.のみ。文字は半角英数字、@前に記号はNG</text></p>
      <label>性別(任意)</label>
      <p><input type="radio" name="sex" value="0"<?php if(isset($sex) && $sex==="0"){echo 'checked';} ?>>男性
        <input type="radio" name="sex" value="1"<?php if(isset($sex) && $sex==="1"){echo 'checked ';} ?>>女性
        <input type="radio" name="sex" value="2"<?php if(isset($sex) && $sex==="2"){echo 'checked';} ?>>その他
      </p>
      <label>現在のパスワード<text class="attention">(必須)</text></label>
      <p><input type="password" name="password" value=""></p>
      <p><text class="attention">*パスワードを変更する際は以下の欄に入力してください<text></p>
      <label>新しいパスワード(任意)</label>
      <p><input type="password" name="new_password" value="<?php if(isset($new_password)){ echo $this->escape($new_password);}else{echo '';}?>"></p>
      <label>確認のため新しいパスワードを再入力してください</label>
      <p><input type="password" name="confirm_password" value="<?php if(isset($confirm_password)){ echo $this->escape($confirm_password); }else{echo '';}?>"></p>
      <input type="hidden" name="csrf_token" value="<?php if(isset($token)){ echo $this->escape($token); }else{echo '';}?>">
      <button>登録する</button>
    </form>
  </article>
  <p><a href="<?php echo $this->escape($base_url); ?>/personal">マイページへ戻る</a></p>
</div>
