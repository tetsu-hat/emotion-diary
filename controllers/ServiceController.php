<?php
class ServiceController extends Controller
{
  protected $controller_actions = array(
    'index',
    'emotions'
  );

  public function indexAction($parameters) {
    $user = $this->session->get('user');
    $parameters = array_merge($parameters, array('user'=>$user));
    //利用方法や操作説明
    return $this->render($parameters);
  }

  public function emotionsAction($parameters) {
    //各感情の解説および本の紹介
    $user = $this->session->get('user');

    //感情とその一言を取得
    $diary_repository = $this->db_manager->getRepository('diary');

    $devides = $diary_repository-> getKindsDevides();

    for($i=0;$i++;$i<41) {
      $emotions[$i] = $diary_repository->getKindsEmotions($i);
    }

    //各感情の説明および分類を取得
    $parameters = array_merge($parameters, array('user'=>$user,'emotions'=>$emotions));
    $this->render($parameters);
  }
}
