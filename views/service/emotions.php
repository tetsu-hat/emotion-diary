<article>
for3回(ポジねが他)
<?php for($i=0;$i++;$i<3): ?>
  <section>
    <h2><?php if(isset($devides)){echo $this->escape($devides[$i]);}else{echo "取得失敗";}?></h2>
    <?php if(isset($emotions)): ?>
  <?php for($j=0;$j++;$j<count($emotions)): ?>
  <label><?php echo $this->escape($emotions['emotion_name']); ?></label>
  <p><?php echo $this->escape($emotions['mean']); ?></p>
<?php endfor; ?>
<?php endif; ?>
</section>
<?php endfor; ?>
</article>
<a href="<?php echo $base_url; ?>/">トップページへ戻る</a>
