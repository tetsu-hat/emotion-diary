<?php$this->setLayout('title','感情分析') ?>
<div>
  <article>
<form action="<?php echo $base_url; ?>/account/emotions" method="post"> method="post">
  <select name="period">
    <option value="week">今週・先週</option>
    <option value="month">今月・先月</option>
    <option value="half_year">半年</option>
  </select>
    <button type="submit" value = "見る">
  </form>
</article>

  <article>
    <p>日記をつけ始めてからの総数</p>
    <table>
      <tr>
        <th>分類</th>
        <th>全ての数</th>
    <?php for($i=0;$i++;$i<3): ?>
      <td>
    <?php if (isset($count_devides[$i])){echo ($this->escape($count_devides['$i']['name'])).':'.($this->escape($total_count_devides[$i]));}else{echo '取得失敗';}?>
    </td>
    <td>
    <?php if (isset($percentage_total_count_devides[$i])){echo ($this->escape($percentage_total_count_devides[$i]));}else{echo '取得失敗';}?>
  </td>
    <?php endfor; ?>
  </table>
    </article>

    <?php for($i=0;$i++;$i<3): ?>
    <article>
      <label><?php if(isset($count_devides[$i]['name'])){echo $this->escape($count_devides[$i]['name']);}else{echo '取得失敗';}?></label>
      <table>
        <tr>
          <th>感情</th>
          <th><?php if(isset($perood_after['name'])){echo $this->escape($period_after['name']);}else{echo '取得失敗';} ?></th>
          <th><?php if(isset($perood_before['name'])){echo $this->escape($period_before['name']);}else{echo '取得失敗';}?></th>
        </tr>
        <?php for($j=0;$j++;$j<$count_devides[$i]['count']): ?>
          <tr>
            <td><?php if(isset($emotions[$i]['name'])){echo $this->escape($emotions[$i]['name']);}else{echo '取得失敗';}?></td> 感情
            <td><?php if(isset($emotions_devides['after'][$i][$j])){echo $this->escape($emotions_devides['after'][$i][$j]);} else{echo '取得失敗';}?>件</td>
            <td><?php if(isset($emotions_devides['before'][$i][$j])){echo $this->escape($emotions_devides['before'][$i][$j]);} else{echo '取得失敗';}?>件</td>
          </tr>
        <?php  endfor; ?>
        <tr>
          <tb>合計</tb>
          <tb><?php if(isset($total_count['after'][$i])){echo $this->escape($total_count['after'][$i]);}else{echo '取得失敗';} ?>件</tb>
          <tb><?php if(isset($total_count['before'][$i])){echo $this->escape($total_count['before'][$i]);}else{echo '取得失敗';} ?>件</tb>
          <tr>
          </table>
        </article>
      <?php endfor; ?>
      </div>
<a href="<?php echo $this->escape($base_url); ?>/">トップページへ戻る</a>
