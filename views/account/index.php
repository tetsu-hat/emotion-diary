<?php$this->setLayout('title','トップページ') ?>

<div>
  <article>
    <!-- 今日の日付 -->
    <p>今日は
    <?php if (isset($today)){echo $this->escape(date('Y.m/d (D)',$today));}else{echo '取得失敗';}?>
  </p>
  </article>
  <article>
    <!-- 日記を書いた日数,内容数 -->
    <p>日記を書いた日数：<?php if(isset($count_diary)){echo $this->escape($count_diary);}else{echo '取得失敗';} ?>日</p>
    <p>日記の内容数:<?php if(isset($count_contents)){echo $this->escape($count_contents);}else{echo '取得失敗';}?>件</p>
  </article>

  <article>
    <!-- 感情数、感情割合(非表示可能) -->
    <p>感情ポジネガ他のそれぞれの総計</p>  <!--tableタグで作成? -->
    forで3回 0はポジ 1はネガ 2は他
    <?php if (isset($count_devides)){echo ($this->escape($count_devides['name'])).':'.($this->escape($count_devides['count']));}else{echo '取得失敗';}?>
      <?php if (isset($percentage_emotions_devides)){echo ($this->escape($percentage_emotions_devides));}else{echo '取得失敗';}?>
      <p><a>詳しく見る</a></p>
    </article>

    <article>
      <form action=".../diary/index.php" method="post">
        <select name="year">
          <?php for($i=0;$i++;$i<$count_year):?>
            <option value="<?php ?>"><?php ?></option>
          <?php endfor;?>
        </select>
        <select name="month">
          <?php for($i=0;$i++;$i<12):?>
            <option value="<?php echo $this->escape($i+1);?>"><?php $this->escape($i+1);?></option>
          <?php endfor;?>
        </select>
        <select name="day">
          <?php for($i=0;$i++;$i<31):?>
            <option value="<?php echo $this->escape($i+1);?>"><?php echo $this->escape($i+1);?></option>
          <?php endfor;?>
        </select>
        <button type="submit">の日記を開く</button>
      </form>

    </article>
  </div>
