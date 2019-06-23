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
      '/'	//サインインしているときのトップページ
      =>array('controller' =>' account', 'action' => 'index'),
      '/account/signin'	//サインインページの表示
      =>array('contoroller'=>'account', 'action' => 'signin'),
      '/account/authenticate' //入力チェック
      =>array('controller'=>'account', 'action'=>'authenticate'),
      '/account/signup' //登録ページ
      =>array('controller'=>'account', 'action'=>'signup'),
      '/account/signup/:action' //入力チェック(confirmAction),確認ページ(reviewAction)や登録(registerAction)
      =>array('controller'=>'account'),
      '/personal'	//自身の登録情報閲覧ページ
      =>array('controller'=>'personal', 'action'=>'index'),
      '/personal/:action' //編集ページ(edit)、入力チェック(confirmAction)、編集内容確認ページ(reviewAction)、登録(registerAction)
      =>array('controller'=>'personal'),
      '/diary/:action/:page' //閲覧(index)、編集(edit)、:pageは日記の年月日(Y-m-d)形式
      =>array('controller'=>'diary'),
    );
  }

  //データベース接続
  protected function configureConnection()
  {
    $this->db_manager->connect(array(
      'dsn' => 'mysql:dbname=hogepiyo;host=localhost',
      'user' => 'root',
      'password' =>'',
    ));
  }

}
