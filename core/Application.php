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

  //リクエストを受けてから出力に至るまでの全体の流れの処理
  public function run()
  {
    try{
      $parameters = $this->router->pathMatch($this->request->getPathInfo());

      if($parameters) {
        throw new HttpNotFoundException('Not route found for '.$this->request->getPathInfo());
      }
      $controller = $parameters['controller'];
      $action = $parameters['action'];
      //ここでコントローラの実行
      $this->runControllerAction($controller, $action, $parameters);

    }catch(HttpNotFoundException $e){
      $this->render404($e);
    }catch(UnauthenticatedActionException $e) {
      $controller = $this->signin_action['controller'];
      $action = $this->signin_action['action'];
      $this->runControllerAction($controller, $action, array());
    }
    //出力処理
    $this->response->output();
  }

  //404出力
  public function render404($e)
  {
    $this->router->setStatusCode('404', 'not found');
    if($this->debug) {
      $message = $e->getMessage().'not found';
    } else {
      $message = 'not found';
    }
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    $output_content = <<<EOT
    <!DOCTYPE html>
    <html lang="ja">
    <head>
    <meata charset="utf-8">
    <title>404</title>
    <head>
    <body>
    $message
    </body>
EOT;

    $this->response->store($output_content);
  }

  //コントローラの呼び出しから出力内容を返す処理まで
  public function runControllerAction($controller, $action, $parameters)
  {
    //コントローラの有無を確認してあればそのファイルを読み込みクラスをインスタンス化
    $controller_name = ucfirst($controller).'Controller';
    $controller_file = $this->getControllersDir().$controller_name.'.php';

    if (!class_exists($controller_name)) {
    if (is_readable($controller_file)) {
      require_once($controller_file);
    } else {
      throw new HttpNotFoundException('Not class found for '.$controller_name);
    }
  }

      $controller = new $controller_name($this);

      $content = $controller->run($action,$parameters);

      $this->response->store($content);
  }

}
