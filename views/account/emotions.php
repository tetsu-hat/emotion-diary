<?php $this->setTitle('title','感情分析') ?>
<div>
  <div  class="topImage">
  <img src="<?php echo $this->escape($stage_url); ?>images/bookImage2.jpg">
  </div>

  <article>
    <p>日記の出来事の総数は<?php echo '『 '.$this->escape($total_count_contents['get_value']).' 』'; ?></p>
    <table>
      <tr>
        <th>分類</th>
        <th>全ての数</th>
      </tr>
      <?php for($i=0;$i<3;$i++): ?>
        <tr>
          <td>
            <!-- ポジティブ・ネガティブ・他等の分類名 -->
            <?php if (isset($count_devides['total'][$i])){echo $this->escape($count_devides['total'][$i]['name']);}?>
          </td>
          <td class="countDevide">
            <?php if (isset($count_devides['total'][$i])){echo $this->escape($count_devides['total'][$i]['get_value']);}?>
          </td>
        </tr>

      <?php endfor; ?>
    </table>
  </article>
  <br>
  <article>
    <form action="<?php echo $base_url; ?>/account/emotions" method="post">
      <select name="period">
        <option value="week" <?php if(isset($request_period) && $request_period==='week'){echo 'selected';} ?>>週間</option>
        <option value="month" <?php if(isset($request_period) && $request_period==='month'){echo 'selected';} ?>>月間</option>
        <option value="half_year" <?php if(isset($request_period) && $request_period==='half_year'){echo 'selected';} ?>>半年間</option>
      </select>
      <button type="submit">見る</button>
    </form>
  </article>

  <br>
  <?php for($i=0;$i<3;$i++): ?>
    <article>
      <p><?php if(isset($count_devides['total'][$i]['name'])){echo $this->escape($count_devides['total'][$i]['name']);}else{echo '取得失敗';}?></p>
      <table>
        <tr>
          <th>感情</th>
          <!-- 期間名(前半) -->
          <th>
            <?php if(isset($period['before']['name'])):?>
            <?php echo $this->escape($period['before']['name']);?>
          <br>
          <?php echo  $this->escape('('.date('n/j',strtotime($period['before']['first'])).'~'.date('n/j',strtotime($period['before']['last'])).')');?>
        <?php else: ?>
          <?php echo '取得失敗';?>
        <?php endif;?>
        </th>
          <!-- 期間名(後半) -->
          <th>
              <?php if(isset($period['after']['name'])):?>
              <?php echo $this->escape($period['after']['name']);?>
            <br>
            <?php echo  $this->escape('('.date('n/j',strtotime($period['after']['first'])).'~'.date('n/j',strtotime($period['after']['last'])).')');?>
          <?php else: ?>
            <?php echo '取得失敗';?>
          <?php endif;?>
          </th>

    </tr>
    <?php for($j=0;$j<(count($count_emotions['after'][$i])-1);$j++): ?>
      <tr>
        <!-- 感情名 -->
        <td><?php if(isset($count_emotions['after'][$i][$j]['name'])){echo ($j+1).'.'.$this->escape($count_emotions['before'][$i][$j]['name']);}else{echo '取得失敗';}?></td>
        <!-- 前半・感情数 -->
        <td class="countEmotion"><?php if(isset($count_emotions['before'][$i][$j]['get_value'])){echo $this->escape($count_emotions['before'][$i][$j]['get_value']);} else{echo '取得失敗';}?>件</td>
        <!-- 後半・感情数 -->
        <td class="countEmotion"><?php if(isset($count_emotions['after'][$i][$j]['get_value'])){echo $this->escape($count_emotions['after'][$i][$j]['get_value']);} else{echo '取得失敗';}?>件</td>

      </tr>
    <?php  endfor; ?>
    <tr>
      <td class="count">合計</td>
      <td class="countEmotion"><?php if(isset($count_emotions['before'][$i]['total'])){echo $this->escape($count_emotions['before'][$i]['total']);}else{echo '取得失敗';} ?>件</td>
      <td class="countEmotion"><?php if(isset($count_emotions['after'][$i]['total'])){echo $this->escape($count_emotions['after'][$i]['total']);}else{echo '取得失敗';} ?>件</td>
      <tr>
      </table>
    </article>
  <?php endfor; ?>
  <p><a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a></p>
</div>
