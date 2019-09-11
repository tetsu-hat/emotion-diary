<?php
class ServiceController extends Controller
{
  protected $controller_actions = array();
  //利用方法や操作説明
  public function indexAction($parameters)
  {
    $user = $this->session->get('user');
    $parameters = array_merge($parameters, array('user'=>$user));
    return $this->render($parameters);
  }

  public function emotionsAction($parameters)
  {
    //各感情の解説および本の紹介
    $user = $this->session->get('user');
    $diary_repository = $this->db_manager->getRepository('diary');
    $kinds=$this->getKinds();
    array_shift($kinds['emotions']);
    $kinds['emotions'] = array_values($kinds['emotions']);
    for ($i=0;$i<count($kinds['emotions']);$i++) {
      if($kinds['emotions'][$i]['devide_id'] === '0'){
        $emotions_of_positive[] = $kinds['emotions'][$i];
      } else if($kinds['emotions'][$i]['devide_id'] === '1'){
        $emotions_of_negative[] = $kinds['emotions'][$i];
      } else if($kinds['emotions'][$i]['devide_id'] === '2'){
        $emotions_of_other[] = $kinds['emotions'][$i];
      }
    }
    $emotions_array=array($emotions_of_positive,$emotions_of_negative,$emotions_of_other);
    for ($i = 0;$i <count($emotions_array);$i++) {
      $emotion_of_devides[]=$emotions_array[$i];
    }
    $parameters = array_merge($parameters, array('user'=>$user,'devides'=>$kinds['devides'],'emotion_of_devides'=>$emotion_of_devides));
    return  $this->render($parameters);
  }
}
