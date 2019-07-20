<?php$this->setLayout('title','日記編集ページ') ?>
<div>
  <form action="<?php echo $base_url.'/diary/register'; ?>" method="post">
  <article>
    <!-- (年月日曜日を表示する箇所) -->
    <h2><?php if(isset($date)){echo $this->escape(date('Y年 m/d (D)',$date));}else{echo '取得失敗';} ?></h2>
    <input name="date" type="hidden" value="<?php if(isset($date)){echo $this->escape(date('Y-m-d',$date));}else{echo '取得失敗';} ?>">
      <button type= "submit" value="日記を保存">
    </article>
    <?php if(isset($errors)){$this->render('errors',array('errors'=>$errors));}?>
      <article>
        <?php for($i=0;$i++;$i<count($feeling)):?>
          <select name="feeling">
        <option value="<?php echo $i; ?>"><?php echo $this->escape($feeling[$i]) ?></option>
      </select>
      <?php endfor;?>
        <!-- 入力箇所の部分*5  -->
        <?php for($i=0;$i++;$i<5):?>
          <section>
            <h3>No.<?php echo ($i+1); ?></h3>

            <label>状況</label>
            <select name="situation_.<?php echo $i ?>">
              <?php if(isset($kinds_situations) && isset($situations)): ?>
                <?php for($j=0;$j++;$j<count($kinds_situations)):?>
                  <option value="<?php echo $j?>"><?php  echo $this->escape($kinds_situations[$j]);?></option>
                <?php endfor;?>
              <?php endif;?>
            </select>

            <label>感情</label>
            <select name="emotions_.<?php echo $i ?>">
              <?php if(isset($kinds_emotions) && isset($emotions)): ?>
                <?php for($k=0;$k++;$k<count($kinds_emotions)):?>
                  <option value="<?php echo $k;?>"><?php echo $this->escape($kinds_emotions[$k]);?></option>
                <?php endfor;?>
              <?php endif; ?>
            </select>

            <label>内容</label>
            <textarea name = "content_.<?php echo $i ?>" value="<?php if(isset($contents[$i])) {echo $this->escape($content[$i]);}else{echo '';} ?>"></textarea>
          </section>
        <?php endfor;?>

      </article>

      <article>
        <!-- (写真を選択する箇所) -->
      </article>
      <input type="hidden" name="csrf_token" value="<?php if(isset($token)){echo $this->escape($token);}?>">
      <button type="submit">保存</button>
    </form>
    <a href="<?php echo $this->escape($base_url); ?>/diary/index/<?php if(isset($date)){echo $this->escape($date);} ?>">閲覧ページへ戻る</a>
        </div>
