<?php
class Session
{
  //セッションが使える状況、セッション状況の判定、ログイン状況の判定に使うプロパティ
  protected static $session_started = false;
  protected static $authenticated = false;
  //コンストラクタ セッションスタートしてるか確認してスタート
  public function __construct()
  {
    if(self::$session_started === false) {
      session_start();
      self::$session_started = true;
      header("Cache-Control: no-cache");
      header("Pragma: no-cache");
      header("Expires:-1");
    }
  }

  public function set($name, $value)
  {
    $_SESSION[$name] = $value;
  }

  public function get($name,$premise = null)
  {
    if(isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return $premise;
  }

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
      session_regenerate_id();
      $this->authenticated =true;
    }
  }
  //ログイン状況の判定の値を定義
  public function setAuthenticated($bool)
  {
    $this->set('_authenticated', $bool) ;
    $this->redefinitionSessionId();
  }
  //ログイン状況の判定の値を取得
  public function isAuthenticated()
  {
    return $this->get('_authenticated');
  }
}
