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
      return $_GET[$name];
    }
    return null;
  }

  //リクエスト$_POSTを取得して返す
  public function getPost($name)
  {
    if(isset($_POST[$name])) {
      return $_POST[$name];
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
    if ($_SERVER['HTTPS'] !== null) {
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
  //注:ベースURLはこのアプリに置ける造語。ホスト部分より後ろからフロントコントローラまでの値のこと
  public function baseUrl()
  {
    $script_name = $_SEVER['SCRIPT_NAME'];
    $request_uri = $this->getUri();

    if (strpos($request_uri, $script_name === 0)) {
      return $script_name;
    } else if (strpos($request_uri, dirname($script_name) === 0)) {
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
      $hoge = strstr($request_uri, '?', false );
      return $path_info = ltrim(rtrim($request_uri, $hoge), $base_url);
    }
    return $path_info = ltrim($request_uri, $base_url);
  }
}
