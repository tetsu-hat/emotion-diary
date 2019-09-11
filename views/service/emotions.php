<?php $this->setTitle('title', '感情一覧') ?>
<div id="serviceEmotions">
  <div class="topImage">
  <img src="<?php echo $this->escape($stage_url);?>images/bookImage5.jpg">
  </div>
<article>
<?php for($i=0;$i<3;$i++): ?>
  <article>
    <p class="devides"><?php if(isset($devides)){echo $this->escape($devides[$i]['name']);}else{echo "取得失敗";}?></p>
    <?php if(isset($emotion_of_devides)): ?>
  <?php for($j=0;$j<count($emotion_of_devides[$i]);$j++): ?>
  <label><?php echo ($j+1).'.'.$this->escape($emotion_of_devides[$i][$j]['name']); ?></label>
  <p><?php echo $this->escape($emotion_of_devides[$i][$j]['mean']); ?></p>
<?php endfor; ?>
<?php endif; ?>
</article>
<?php endfor; ?>
</article>
<p class="book">参考:「感情」の解剖図鑑 仕事もプライベートも充実させる、心の操り方/苫米地秀人</p>
<p><a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a></p>
</div>
