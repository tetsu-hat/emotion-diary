<?php
class Session
{
  //セッションが使える状況、セッション状況の判定、ログイン状況の判定に使うプロパティ
  protected $session_started = false;
  protected $authenticated = false;
  //コンストラクタ セッションスタートしてるか確認してスタート
  public function __construct()
  {
    if($session_started !== false) {
      session_start();
      $this->session_started = true;
    }
  }
  //$_SESSION['hoge']に値を格納する
  public function set($name, $value)
  {
    $_SESSION[$name] = $value;
  }
  //$_SESSION['hoge']を取得。ない場合はnullを。
  public function get($name)
  {
    if(isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return null;
  }

  //$_SESSION['hoge']をunset
  public function release($name)
  {
    unset($_SESSION[$name]);
  }

  //$_SESSIONをclear
  public function clear()
  {
    $_SESSION = array();
  }

  //sessionIdの再定義
  public function redefinitionSessionId()
  {
    if($this->authenticated !==true) {
      session_regenerate_id($delete_old_session = true);
      $this->authenticated =true;
    }
  }

  //ログイン状況の判定の値を定義
  public function serAuthenticated($bool)
  {
    $this->set('_authenticated', $bool) ;
    $this->redefinitionSessionId();
  }
  //ログイン状況の判定の値を取得
  public function isAuthenticated()
  {
    return get('_authenticated');
  }
}
