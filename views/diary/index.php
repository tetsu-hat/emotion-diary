<?php $this->setTitle('title','日記閲覧ページ') ?>
<div>
  <div class="topImage">
  <img src="<?php echo $this->escape($stage_url); ?>images/noteImage9.jpg">
  </div>
  <article>
    <!-- 年月日と曜日を表示する箇所 -->
    <?php if(isset($errors)){echo $this->render('errors',array('errors'=>$errors));}?>
    <form action="<?php echo $base_url.'/diary/index'; ?>" method="post">
      <select name="year">
        <?php for($i=0;$i>-($select_year['period']+1);$i--): ?>
          <option value="<?php echo $this->escape($select_year['last']+$i); ?>"
            <?php if(isset($select_year['last'])&&isset($date)&&(date("Y",strtotime($date))===(date("Y",strtotime($select_year['last']+$i))))){echo 'selected';} ?>>
            <?php echo $this->escape($select_year['last']-$i); ?></option>
          <?php endfor;?>
        </select>
        年
        <select name="month">
          <?php for($i=0;$i<12;$i++):?>
            <option value="<?php echo $this->escape($i+1);?>"
              <?php if(isset($date)&&(($i+1)==date("n",strtotime($date)))){echo 'selected';} ?>>
              <?php echo  $this->escape($i+1);?></option>
            <?php endfor;?>
          </select>
          月
          <select name="day">
            <?php for($i=0;$i<31;$i++):?>
              <option value="<?php echo $this->escape($i+1);?>"
                <?php if(isset($date)&&(($i+1)==date("j",strtotime($date)))){echo 'selected';} ?>>
                <?php echo $this->escape($i+1);?></option>
              <?php endfor;?>
            </select>
            日のページを<button type="submit">開く</button>
          </form>
          <h2><?php if(isset($date)){echo $this->escape(date('Y年 n/j (D)',strtotime($date)));}else{echo '取得失敗';} ?></h2>
        </article>

        <?php if($diary===false): ?>
          <!-- 日記がそもそもない時 -->
          <article>
            <p>日記は書かれていません。真っさらです。</p>
            <form action="<?php echo $this->escape($base_url); ?>/diary/edit/<?php echo $this->escape($date); ?>" method="post">
              <button type="submit">日記を書く</button>
            </form>
            <div class="diaryImage">
            <img src="<?php echo $this->escape($stage_url); ?>images/noteImage6.jpg">
            </div>

          </article>
        <?php else: ?>
          <!-- 日記がある時 -->
          <article>
            <form action="<?php echo $this->escape($base_url); ?>/diary/edit/<?php echo $this->escape($date); ?>" method="post">
              <button type="submit">日記を書く</button>
            </form>
            <p>今日の調子は<?php if(isset($diary['feeling'])){echo '【'.$this->escape($diary['feeling']).'】日。';}else{echo '取得失敗';}  ?></p>
            <?php if(empty($date_contents)): ?>
              <!-- 日記はあるが内容がない時 -->
              <p>出来事は書かれていません。</p>
            <?php else: ?>
              <!-- 日記も内容もある時 -->
              <?php for ($i=0;$i<count($date_contents); $i++): ?>
                <article class="contents">
                  <p><?php echo '【'.($this->escape($date_contents[$i]['situation'])).'/' ?>
                  <?php echo ($this->escape($date_contents[$i]['emotion'])).'】' ?></p>

                  <p class="content"><?php echo $this->escape($date_contents[$i]['content']); ?></p>
                </article>

              <?php endfor; ?>
            <?php endif; ?>
            <article  class="today_image">
              <!-- 写真を表示する箇所 -->
              <?php if($diary['picture']===''):?>
                <p>この日の写真は選択されていません</p>
              <?php else: ?>
                <img src="<?php echo $this->escape($stage_url.'../images/'.$diary['picture']);  ?> ">
              <?php endif; ?>

            </article>
          <?php endif; ?>
        </article>

        <p><a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a></p>
      </div>
