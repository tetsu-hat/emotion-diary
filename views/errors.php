<div class="errors">
  <ul>
  <?php for($i=0;$i<count($errors);$i++): ?>
  <li><?php echo $this->escape($errors[$i]); ?></li>
<?php endfor; ?>
</ul>
</div>
