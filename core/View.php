<?php
class View
{
  //viewディレクトリまでのパス、DBから取得した値、titleの値を格納するプロパティ
  protected $directory_path;
  protected $parameters;
  protected $titles;
  //コンストラクタ viewディレクトリまでのパス、DBから取得した値を格納
  public function __construct($path, $parameters)
  {
    $this->directory_path = $path;
    $this->parameters = $parameters;
  }
  //titleの値を格納する
  public function setTitle($name, $value)
  {
    $this->titles[$name] = $value;
  }
  //出力内容を構成してそれを返す。
  public function render($path,$elements, $layout = false)
  {
    $path = $this->directory_path.'/'.$path.'.php';
    $this->parameters =array_merge($this->parameters,$elements);
    $parameters = extract($this->parameters);
    ob_start();
    ob_implicit_flush(0);
    require $path;
    $content = ob_get_clean();
    $_layout = $layout;
    if ($_layout !== false) {
      $elements = array_merge($this->titles, array('content'=>$content));
      $content = $this->render($_layout,$elements);
      return $content;
    }
    return $content;
  }
  //エスケープ処理
  public function escape($string)
  {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}
