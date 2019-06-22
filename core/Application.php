<?php
abstract class Application
{
  protected $debug = false;
  //インスタンスを格納するプロパティ
  protected $request;
  protected $router;
  protected $session;
  protected $db_manager;
  protected $response;

  //コンストラクタ
  public function __construct($debug)
  {
    $this->setDebugMode($debug);
    $this->initialize();
    $this->configure();
  }

  //デバッグの設定
  protected function setDebugMode($debug)
  {
    if($debug) {
      ini_set('display_errors', "On");
      error_reporting(-1);
    } else {
      ini_set('display_errors', "Off");
    }
  }

  //最低限の準備のため各クラスのインスタンス化と格納
  protected function initialize()
   {
    $this->request = new Request();
    $this->router = new Router($this->registerRoutes());
    $this->session = new Sesison();
    $this->db_manager =new DbManager();
    $this->response = new Response();
  }

  abstract public function getDirectoryRoot();

  abstract public function registerRoutes();

  abstract protected function configureConnection();

  //各インスタンスを返す
  public function getRequest()
   {
    return $this->request;
  }

  public function getRouter()
  {
    return $this->router;
  }

  public function getSession()
  {
    return $this->session;
  }

  public function getDbManager()
   {
    return $this->request;
  }

  public function getResponse()
   {
    return $this->response;
  }
  //各ディレクトリまでのパスを返す
  public function getControllersDir()
  {
return $this->getDirectoryRoot().'/controllers';
  }

  public function getViwesDir()
  {
    return $this->getDirectoryRoot().'/views';
  }

  //todo リクエストを受けてから出力に至るまでの全体の流れの処理
  public function run()
  {

  }

  //todo 404出力
  public function render404($e)
  {

  }

  //todo コントローラの呼び出しから出力内容を返す処理まで
  public function runActionController()
  {

  }

}
