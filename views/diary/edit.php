<?php $this->setTitle('title','日記編集ページ') ?>
<div>
  <div class="topImage">
  <img src="<?php echo $this->escape($stage_url); ?>images/noteImage3.jpg">
  </div>
  <form enctype="multipart/form-data" action="<?php echo $base_url ?>/diary/register/<?php echo $this->escape($date); ?>" method="post">
    <article>
      <!-- (年月日曜日を表示する箇所) -->
      <h2><?php if(isset($date)){echo $this->escape(date('Y年 n/d (D)',strtotime($date)));}else{echo '取得失敗';} ?></h2>
      <input name="date" type="hidden" value="<?php if(isset($date)){echo $this->escape(date('Y-m-d',strtotime($date)));}else{echo '取得失敗';} ?>">
    </article>
    <?php if(count($errors) !== 0){echo $this->render('errors',array('errors'=>$errors));}?>
    <article>
      <p>今日の調子は
        <select name="feeling">
          <?php for($i=0;$i<count($kinds['feelings']);$i++):?>
            <option value="<?php echo $i; ?>" <?php if(isset($diary['feeling_id'])&&($kinds['feelings'][$i]['id'] == $diary['feeling_id'])){echo 'selected';}  ?>>
              <?php echo $this->escape($kinds['feelings'][$i]['name']) ?></option>
          <?php endfor;?>
        </select>
        １日！
      </p>
      <!-- 入力箇所の部分*5  -->
      <?php for($i=0;$i<5;$i++):?>
        <article>
          <p class="contentSelectArea">
            <label>状況</label>
            <select name="situation_<?php echo $i ?>">
              <?php if(isset($kinds['situations'])): ?>
                <?php for($j = 0;$j<count($kinds['situations']);$j++):?>
                  <option value="<?php echo $j?>" <?php if(isset($date_contents[$i]['situation_id'])&&($kinds['situations'][$j]['id'] == $date_contents[$i]['situation_id'])){echo 'selected';}?>>
                    <?php  echo $this->escape($kinds['situations'][$j]['name']);?></option>
                <?php endfor;?>
              <?php endif;?>
            </select>

            <label>感情</label>
            <select class="emotionsSelect" name="emotion_<?php echo $i ?>">
              <?php if(isset($kinds['emotions'])): ?>
                <?php for($k = 0;$k<count($kinds['emotions']);$k++):?>
                  <option class="emotionsOptions" value="<?php echo $k;?>" data-text="<?php echo $this->escape($kinds['emotions'][$k]['mean']); ?>"
                    <?php if(isset($date_contents[$i]['emotion_id'])&&($kinds['emotions'][$k]['id'] == $date_contents[$i]['emotion_id'])){echo 'selected';}?>>
                    <?php echo $this->escape($kinds['emotions'][$k]['name']);?></option>
                <?php endfor;?>
              <?php endif; ?>
            </select>
          </p>
          <p class="emotionsBalloon"></p>
          <p><label>内容</label></p>
          <textarea name="content_<?php echo $i ?>"  rows="4" cols="40" value=""><?php if(isset($date_contents[$i]['content'])) {echo $this->escape($date_contents[$i]['content']);}else{echo '';} ?></textarea>
        </article>
      <?php endfor;?>

    </article>

    <article>
      <!-- (写真を選択する箇所) -->
      <p>今日の一枚<input name="uploadfile" type="file" value="<?php if(isset($diary['picture'])){echo $this->escape($diary['picture']);}?>"></p>
    </article>
    <article>
      <?php if($diary_count === '1'): ?>
      <p><input type="checkbox" name="delete" value="1">保存されている日記をまっさらにする</p>
    <?php endif; ?>
    </article>
    <input type="hidden" name="csrf_token" value="<?php if(isset($token)){echo $this->escape($token);}?>">
    <button type="submit">日記を保存</button>
  </form>
  <p><a href="<?php echo $this->escape($base_url); ?>/diary/index/<?php if(isset($date)){echo $this->escape($date);} ?>">閲覧ページへ戻る</a></p>
</div>

<script type="text/javascript" src="<?php echo $this->escape($stage_url);?>js/balloon.js"></script>
