<?php$this->setLayout('title','日記閲覧ページ') ?>
<div>
  <article>
  <!-- 年月日と曜日を表示する箇所 -->
  <?php if(isset($errors)){$this->render('errors',array('errors'=>$errors));}?>
    <form action="<?php echo $base_url.'/diary/edit'; ?>" method="post">
      <select name="year">
        <?php for($i=0;$i--;$i>-$select_year['period']):?>
          <option value="<?php echo $this->escape($select_year['last']+$i); ?>"
            <?php if(isset($select_year['last'])&&isset($date)&&(date("Y",strtotime($date))===(date("Y",strtotime($select_year['last']+$i))))){echo 'selected';} ?>>
            <?php echo $this->escape($select_year['last']-$i); ?></option>
        <?php endfor;?>
      </select>
      年
      <select name="month">
        <?php for($i=0;$i++;$i<12):?>
          <option value="<?php echo $this->escape($i+1);?>"
              <?php if(isset($date)&&(date("m",strtotime($date))===(date("m",strtotime($i+1))))){echo 'selected';} ?>>
            <?php $this->escape($i+1);?></option>
        <?php endfor;?>
      </select>
      月
      <select name="day">
        <?php for($i=0;$i++;$i<31):?>
          <option value="<?php echo $this->escape($i+1);?>"
            <?php if(isset($date)&&(date("d",strtotime($date))===(date("d",strtotime($i+1))))){echo 'selected';} ?>>
            <?php echo $this->escape($i+1);?></option>
        <?php endfor;?>
      </select>
      日の日記ページを<button type="submit">開く</button>
    </form>
  <h2><?php if(isset($date)){echo $this->escape(date('Y年 m/d (D)',$date));}else{echo '取得失敗';} ?></h2>
</article>
<article>
  <p>この日の総評は<?php if(isset($diary['feeling'])){echo $this->escape($diary['feeling']);}else{echo '取得失敗';}  ?></p>
</article>

<article>
<!-- 取得した内容を表示する箇所 -->
<?php if (isset($date_contents)):?>
<?php for($i = 0;$i++;$i<count($date_contents)): ?>
<section>
  <h3>No.<?php echo $i+1 ?></h3>
<p><?php echo 'シチュエーション:'.($this->escape($situations[$i])) ?></p>
<p><?php echo '気持ち:'.($this->escape($emotions[$i])) ?></p>
<p><?php echo $this->escape($contents[$i]); ?></p>
</section>
<?php endfor; ?>
<?php endif; ?>
</article>

<article>
  <!-- 写真を表示する箇所 -->
  <img>
</article>

<a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a>
</div>
