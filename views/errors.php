<div>
  <?php for($i=0;$i++;$i<count($errors)): ?>
  <p><?php echo $this->escape($errors[$i]); ?></p>
<?php endfor; ?>
</div>
