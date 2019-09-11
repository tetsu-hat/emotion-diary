<?php
class ByPeriod
{
  protected $application;
  protected $db_manager;

  protected $period_month = array('before'=>array('first'=>array('format'=>'Y-m-d','timestamp'=>'-2 month'),'last'=>array('format'=>'Y-m-d','timestamp'=>'-1 month -1day'),'name'=>'先月'),
  'after'=>array('first'=>array('format'=>'Y-m-d','timestamp'=>'-1 month'),'last'=>array('format'=>'Y-m-d','timestamp'=>'now'),'name'=>'今月'));

  protected $period_half_year = array('before'=>array('first'=>array('format'=>'Y-m-d','timestamp'=>'-1 year'),'last'=>array('format'=>'Y-m-d','timestamp'=>'-6 month -1day'),'name'=>'前期'),
  'after'=>array('first'=>array('format'=>'Y-m-d','timestamp'=>'-6 month'),'last'=>array('format'=>'Y-m-d','timestamp'=>'now'),'name'=>'後期'));

  protected $period_week = array('before'=>array('first'=>array('format'=>'Y-m-d','timestamp'=>'-2 week'),'last'=>array('format'=>'Y-m-d','timestamp'=>'-1 week -1day'),'name'=>'先週'),
  'after'=>array('first'=>array('format'=>'Y-m-d','timestamp'=>'-1 week'),'last'=>array('format'=>'Y-m-d','timestamp'=>'now'),'name'=>'今週'));

  public function __construct($application)
  {
    $this->application = $application;
    $this->db_manager = $this->application->getDbManager();
  }
  //リクエストされた期間に対する始まりの日、終わりの日、呼称を返す
  protected function responsePeriod($request_period)
  {
    $period_parameters=array('month'=>array('period'=>$this->period_month,'name'=>'month'),
    'half_year'=>array('period'=>$this->period_half_year,'name'=>'half_year'),
    'week'=>array('period'=>$this->period_week,'name'=>'week'));
    $response_period=array();
    foreach ($period_parameters as $parameter) {
      if (isset($request_period) && $request_period === $parameter['name']) {
        $response_period['before'] = array('first'=>date($parameter['period']['before']['first']['format'],strtotime($parameter['period']['before']['first']['timestamp'])),
        'last'=>date($parameter['period']['before']['last']['format'],strtotime($parameter['period']['before']['last']['timestamp'])),
        'name'=>$parameter['period']['before']['name']
      );
      $response_period['after'] = array('first'=>date($parameter['period']['after']['first']['format'],strtotime($parameter['period']['after']['first']['timestamp'])),
      'last'=>date($parameter['period']['after']['last']['format'],strtotime($parameter['period']['after']['last']['timestamp'])),
      'name'=>$parameter['period']['after']['name']
    );
  }
}
return $response_period;
}
// 登録されている大分類、感情の数を取得。$response_periodと$kinds_emotionsは連想配列であることに注意
protected function getCountByPeriod($user_id,$diary_repository,$response_period=array(),$kinds_emotions=array())
{
  //期間別ポジネガ他取得
  for($i = 0;$i<3;$i++) {
    $count_devides_by_period['after'][$i] = $diary_repository->countDevidesByPeriod($user_id,$i,$response_period['after']['first'],$response_period['after']['last']);
    $count_devides_by_period['before'][$i] = $diary_repository->countDevidesByPeriod($user_id,$i,$response_period['before']['first'],$response_period['before']['last']);
  }
  //期間別ポジネガ他別感情を取得
  for($j = 0;$j<42;$j++) {
    $count_emotions_by_period['after'][$j] = array_merge($diary_repository->countEmotionByPeriod($user_id,$j,$response_period['after']['first'],$response_period['after']['last']),$kinds_emotions[$j]);
    $count_emotions_by_period['before'][$j] = array_merge($diary_repository->countEmotionByPeriod($user_id,$j,$response_period['before']['first'],$response_period['before']['last']),$kinds_emotions[$j]);
  }
  array_shift($count_emotions_by_period['after']);
  array_shift($count_emotions_by_period['before']);
  return array('devides'=>$count_devides_by_period,'emotions'=>$count_emotions_by_period);
}

protected function storeCountEmotionsAgain($count_every_kinds = array())
{
  for($i = 0;$i<3;$i++){
    foreach($count_every_kinds as $count_emotions){
      if($count_emotions['devide_id'] == $i){
        $store_count_emotions[$i][] = $count_emotions;
      }
    }
  }
  return $store_count_emotions;
}

protected function countTotalEmotionsByPeriod($total=array(),$count_emotions=array())
{
  for($i = 0;$i<3;$i++){
    $total[$i]=0;
    foreach ($count_emotions[$i] as $count) {
      $total[$i] += $count['get_value'];
    }
  }
  return $total;
}

public function getCountEmotionsAndDevides($user_id,$request_period,$kinds=array())
{
  $diary_repository = $this->db_manager->getRepository('diary');
  date_default_timezone_set('Asia/Tokyo');
  $today = date("Y-m-d");
  $response_period=$this->responsePeriod($request_period);
  if($response_period===array()){
    return $value=false;
  }
  $kinds_devides = $kinds['devides'];
  $kinds_emotions = $kinds['emotions'];
  $count_conents = $diary_repository->countContents($user_id);
  //ポジネガ他取得
  for($i = 0;$i<3;$i++) {
    $count_devides[$i] = array_merge($diary_repository->countDevides($user_id,$i),$kinds_devides[$i]);
  }
  //期間別ポジネガ他取得、期間別に感情を取得
  $count_every_kinds = $this->getCountByPeriod($user_id,$diary_repository,$response_period,$kinds_emotions);
  //期間別に取得した感情を分類
  $count_emotions_after=$this->storeCountEmotionsAgain($count_every_kinds['emotions']['after']);
  $count_emotions_before=$this->storeCountEmotionsAgain($count_every_kinds['emotions']['before']);
  //$count_emotions_after,$count_emotions_beforeの合計
  $count_total_emotions_after=array();
  $count_total_emotions_before=array();

  $count_total_emotions_after=$this->countTotalEmotionsByPeriod($count_total_emotions_after,$count_emotions_after);
  $count_total_emotions_before=$this->countTotalEmotionsByPeriod($count_total_emotions_before,$count_emotions_before);

  for($i = 0;$i<3;$i++){
    $count_emotions_after[$i]=array_merge($count_emotions_after[$i],array('total'=>$count_total_emotions_after[$i]));
    $count_emotions_before[$i]=array_merge($count_emotions_before[$i],array('total'=>$count_total_emotions_before[$i]));
  }

  $values = array(
    'total_count_contents'=>$count_conents,
    'period'=>$response_period,
    'count_devides'=>array('total'=>$count_devides,'after'=>$count_every_kinds['devides']['after'],'before'=>$count_every_kinds['devides']['before']),
    'count_emotions'=>array('after'=>$count_emotions_after,'before'=>$count_emotions_before)
  );
  return  $values;
}
}

?>
