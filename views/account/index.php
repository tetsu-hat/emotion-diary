<?php $this->setTitle('title','トップページ') ?>

<div>
  <div class="topImage">
  <img src="<?php echo $this->escape($stage_url);?>images/noteImage1.jpg">
  </div>
  <article>
    <!-- 今日の日付 -->
    <p>今日は
      <?php if (isset($today)){echo $this->escape(date('Y年m月d日 (D)',strtotime($today)));}else{echo '取得失敗';}?>
    </p>
  </article>

  <div class="record">
  <article class="diaryCount">
    <!-- 日記を書いた日数,内容数 -->
    <p class="up">日記を書いた日数：<?php if(isset($count_days['get_value'])){echo $this->escape($count_days['get_value']);}else{echo '取得失敗';} ?>日</p>
    <p class="down">日記の内容数 : <?php if(isset($count_contents['get_value'])){echo $this->escape($count_contents['get_value']);}else{echo '取得失敗';}?>件</p>
      <p><a href="<?php echo $base_url; ?>/account/emotions"><p>詳しく見る</a></p>
  </article>

  <article class="emotionTable">
    <table>
      <tr>
        <th>分類</th>
        <th>内容数</th>
      </tr>
      <?php for($i=0;$i<count($count_emotions_devides);$i++):?>
        <tr>
          <td><?php if (isset($emotion_devides)){echo ($this->escape($emotion_devides[$i]['name']));}?></td>
          <td class="countDevide"><?php if (isset($count_emotions_devides)){echo ($this->escape($count_emotions_devides[$i] ['get_value']));}?></td>
        </tr>
      <?php endfor;?>
    </tr>
  </table>

  </article>
</div>

  <article class="openDiary">
    <form action="<?php echo $this->escape($base_url); ?>/diary/index" method="post">
      <p>
      <select name="year">
        <?php for($i=0;$i<$select_year['period'];$i++):?>
          <option value="<?php echo $this->escape($select_year['last']-$i); ?>" <?php if(($select_year['last']-$i)==$select_year['last']){echo 'selected';} ?> ><?php echo $this->escape($select_year['last']-$i); ?></option>
        <?php endfor;?>
      </select>
      年
      <select name="month">
        <?php for($i=0;$i<12;$i++):?>
          <option value="<?php echo $this->escape($i+1);?>" <?php if(($i+1)==date("n",strtotime($today))){echo 'selected';} ?> ><?php echo $this->escape($i+1);?></option>
        <?php endfor;?>
      </select>
      月
      <select name="day">
        <?php for($i=0;$i<31;$i++):?>
          <option value="<?php echo $this->escape($i+1);?>" <?php if(($i+1)==date("j",strtotime($today))){echo 'selected';} ?> ><?php echo $this->escape($i+1);?></option>
        <?php endfor;?>
      </select>
      日の<button type="submit">日記を開く</button>
    </p>
    </form>
  </article>
  <div class="diaryImage">
  <img src="<?php echo $this->escape($stage_url);?>images/noteImage9.jpg">
  </div>
</div>
