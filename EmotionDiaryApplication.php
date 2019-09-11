<?php
class EmotionDiaryApplication extends Application
{
  protected $signin_action = array('controller'=>'account', 'action'=>'signin');
  //親ディレクトリのパスを取得
  public function getDirectoryRoot()
  {
    return dirname(__FILE__);
  }
  //ルーティング定義
  public function registerRoutes()
  {
    return array(
      '/'
      =>array('controller' =>'account', 'action' => 'index'),
      '/account/signin'
      =>array('controller'=>'account', 'action' => 'signin'),
      '/account/authenticate'
      =>array('controller'=>'account', 'action'=>'authenticate'),
      '/account/signup'
      =>array('controller'=>'account', 'action'=>'signup'),
      '/account/signup/:action'
      =>array('controller'=>'account'),
      '/account/signout'
      =>array('controller'=>'account', 'action'=>'signout'),
      '/account/emotions'
      =>array('controller'=>'account', 'action'=>'emotions'),
      '/personal'
      =>array('controller'=>'personal', 'action'=>'index'),
      '/personal/:action'
      =>array('controller'=>'personal'),
      '/diary/:action/:date'
      =>array('controller'=>'diary'),
      '/diary/:action' 
      =>array('controller'=>'diary'),
      '/service/:action'
      =>array('controller'=>'service'),
    );
  }
  //データベース接続
  protected function configureConnection()
  {
    $this->db_manager->connect(array(
      'dsn' => 'mysql:dbname=emo_diary;host=localhost;charset=utf8;',
      'user' => 'root',
      'password' =>'',
    ));
  }
}
