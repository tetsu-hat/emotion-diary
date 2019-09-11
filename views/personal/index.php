<?php $this->setTitle('title','マイページ') ?>
<div>
  <div class="topImage">
  <img src="<?php echo $this->escape($stage_url); ?>images/noteImage1.jpg">
  </div>
  <!-- 入力内容の表示 -->
  <form action="<?php echo $this->escape($base_url); ?>/personal/edit" method="post">
    <label>名前</label>
    <p><?php if(isset($user['name'])){echo $this->escape($user['name']);}else{echo '取得失敗';} ?></p>
    <label>メール</label>
    <p><?php if(isset($user['mail'])){echo $this->escape($user['mail']);}else{echo '取得失敗';} ?></p>
    <p>メールで使える記号は@-_.のみ。文字は半角英数字、@前に記号はNG</p>
    <label>性別</label>
    <p><?php if(isset($user['sex_name'])){echo $this->escape($user['sex_name']);}else{echo '取得失敗';} ?></p>
    <label>パスワード</label>
    <p>●●●●●●●●</p>

    <button>編集する</button>
    </form>

    <a href="<?php echo $this->escape($base_url); ?>/personal/intent"><button class="deleteBtn">日記を処分する</button></a>

    <p><a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a></p>
  </div>
