<?php$this->setLayout('title','退会確認ページ') ?>
<div>
  <article>
    <!-- 確認文章の表示 -->
    <p>消しちゃう？日記の内容は全て破棄されます</p>
    <?php if(isset($errors)){$this->render('errors',array('errors'=>$errors));}?>
    <form action="<?php echo $this->escape($base_url); ?>/personal/delete" method="post">
      <label>パスワード</label>
      <inout value="<?php if(isset($password)){echo $this->escape($password);} ?>">
        <input type="hidden" value="<?php if(isset($password)){echo $this->escape($token);} ?>">
        <button type="submit">退会する</button>
      </form>
    </article>
  </div>
  <a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a>
