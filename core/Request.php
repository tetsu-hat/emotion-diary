<?php
class Request
{
  //リクエストがPOSTか判定
  public function isPost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }
  //リクエスト$_GETを取得して返す
  public function getGet($name)
  {
    if(isset($_GET[$name])) {
      return htmlspecialchars($_GET[$name], ENT_QUOTES, 'UTF-8');
    }
    return null;
  }
  //リクエスト$_POSTを取得して返す
  public function getPost($name)
  {
    if(isset($_POST[$name])) {
      return htmlspecialchars($_POST[$name], ENT_QUOTES, 'UTF-8');
    }
    return null;
  }
  // HOSTを返す、ない場合はサーバ名を返す
  public function getHost()
  {
    if (isset($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME'];
  }
  //sslか判定
  public function isSsl()
  {
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
      return true;
    }
    return false;
  }
  //uriを取得して返す
  public function getUri()
  {
    return $_SERVER['REQUEST_URI'];
  }
  //ベースURLを返す
  //注:ベースURLは造語。ホスト部分より後ろからフロントコントローラまでの値のこと
  public function baseUrl()
  {
    $script_name = $_SERVER['SCRIPT_NAME'];
    $request_uri = $this->getUri();
    if (strpos($request_uri, $script_name)===0) {
      return $script_name;
    } else if (strpos($request_uri, dirname($script_name))===0) {
      return rtrim(dirname($script_name), '/');
    }
    return "";
  }
  //ファイルパスの取得
  public function getPathInfo()
  {
    // リクエストからベースUrlと?以下を取り除く
    $request_uri = $this->getUri();
    $base_url = $this->baseUrl();
    if(strpos($request_uri, '?') !== false){
      $get_path = strstr($request_uri, '?', false );
      return $path_info = substr(rtrim($request_uri, $get_path), strlen($base_url));
    }
    return $path_info = substr($request_uri, strlen($base_url));
  }

  public function getPathStage(){
    $request_uri = $this->getUri();
    $script_name = $_SERVER['SCRIPT_NAME'];
    $path =str_replace($script_name, '', $request_uri);
    $number_stage = mb_substr_count($path,'/');
    $stage='';
    for($i=0;$i<$number_stage;$i++){
      $stage.='../';
    }
    return $stage;
  }
}
