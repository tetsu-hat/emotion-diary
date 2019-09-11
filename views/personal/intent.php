<?php $this->setTitle('title','退会確認ページ') ?>
<div>
  <div class="topImage">
  <img src="<?php echo $this->escape($stage_url); ?>images/noteImage2.jpg">
  </div>
  <article>
    <!-- 確認文章の表示 -->
    <p>消しちゃう？日記の内容は全て破棄されます</p>
    <?php if(isset($errors)){echo $this->render('errors',array('errors'=>$errors));}?>
    <form action="<?php echo $this->escape($base_url); ?>/personal/delete" method="post">
      <label>パスワード</label>
      <p><input type="password" name="password" value="<?php if(isset($password)){echo $this->escape($password);} ?>"></p>
        <input type="hidden" name="csrf_token" value="<?php if(isset($token)){echo $this->escape($token);} ?>">
        <button type="submit">退会する</button>
      </form>
    </article>
    <p><a href="<?php echo $this->escape($base_url); ?>/personal">マイページへ戻る</a></p>
  </div>
