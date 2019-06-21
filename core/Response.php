<?php
class Response
{
  //出力する内容を格納するプロパティやステータスコード等を格納するプロパティ
  protected $content;
  protected $status_code = 200;
  protected $status_text = 'OK';
  protected $redirect_headers = array();
  //ステータス番号200 OKで出力(出力する内容を)
  public function output()
  {
    header('HTTP/1.1 '.$this->status_code.' '.$this->status_text);
    if(isset($this->redirect_headers)) {
      foreach($this->redirect_headers as $name => $url) {
        header($name .':'.$url);
      }
    }
    echo $this->content;
  }
  //contentに出力内容を格納
  public function store($internal_buffer)
  {
    $this->content = $internal_buffer;
  }

  //ステータスコードをセットする
  public function setStatusCode($code, $text)
  {
    $this->status_code = $code;
    $this->status_text = $text;
  }
  //リダイレクトに係る値を配列のプロパティに格納
  public function setRedirectHeaders($name, $url)
  {
    $redirect_headers[$name] = $url;
  }
}
