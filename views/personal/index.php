<?php$this->setLayout('title','マイページ') ?>
<div>
  <!-- 入力内容の表示 -->
  <form action="<?php echo $this->escape($base_url); ?>/personal/edit" method="post">>
    <label>名前</label>
    <p><?php if(isset($name)){echo $this->escape($name);}else{echo '取得失敗';} ?></p>
    <label>メール</label>
    <p><?php if(isset($mail)){echo $this->escape($mail);}else{echo '取得失敗';} ?></p>
    メールで使える記号は@-_.のみ。文字は半角英数字、@前に記号はNG
    <label>パスワード</label>
    <p>●●●●●●●●</p>

    <button>編集する</button>
    </form>
    <a href="<?php echo $this->escape($base_url); ?>/personal/intent">日記を処分する</a>
    <a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a>
  </div>
