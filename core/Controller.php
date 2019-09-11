<?php
abstract class Controller
{
  //使用するインスタンスなどを格納するプロパティ定義
  protected $application;
  protected $request;
  protected $db_manager;
  protected $session;
  protected $response;
  protected $check;
  protected $files;
  protected $controller_name;
  protected $action_name;
  //コンストラクタ インスタンスの格納
  public function __construct($application)
  {
    $this->application = $application;
    $this->request = $this->application->getRequest();
    $this->db_manager = $this->application->getDbManager();
    $this->session = $this->application->getSession();
    $this->response = $this->application->getResponse();
    $this->check = new Check($application);
    $this->files = new Files();
  }
  //hogeControllerのアクションの実行と出力内容を返す処理
  public function run($action,$parameters)
  {
    $this->controller_name = lcfirst(substr(get_class($this), 0,(strlen(get_class($this))-strlen('Controller'))));
    $this->action_name = $action;
    $action_method = $action.'Action';
    //メソッドが存在するか確認。しなければmove404へ
    if(!method_exists($this,$action_method)) {
      return  $this->move404();
    };
    //メソッドが存在したけれどサインインしていない場合
    if($this->isAction($this->action_name,$this->controller_actions) && !$this->session->isAuthenticated()) {
      throw new UnauthenticatedActionException();
    }
    $content = $this->$action_method($parameters);
    return $content;
  }
  // //リクエストされたアクションが存在するか配列より確認
  public function isAction($action,$controller_actions)
  {
    if (in_array($action, $controller_actions) === true) {
      return true;
    }
    return false;
  }
  //要求されたファイルが存在しないとき404の例外を投げる
  public function move404()
  {
    throw new HttpNotFoundException('Not route found '.$this->controller_name.'/'.$this->action_name);
  }
  //指定されたurlへリダイレクト
  public function redirect($path)
  {
    $host = $this->request->getHost();
    $base_url = $this->request->baseUrl();
    $redirect_url = $host.$base_url.$path; //引数の$pathを元にファイルパスを組み立てる
    if ($this->request->isSsl()) {
      $url = 'https://' .$redirect_url;
    } else {
      $url = 'http://'.$redirect_url;
    };
    //ステータスコード302、テキスト Foundを変更してResponseクラスのプロパティに格納
    $this->response->setStatusCode(302, 'Found');
    //Responseクラスの$redirect_headersにurlを格納
    $this->response->setRedirectHeaders('Location', $url);
  }
  //viewクラスをインスタンス化して見た目を構成していく
  protected function render($parameters, $specified_path = null, $layout = 'layout')
  {
    $views_directory_path = $this->application->getViwesDir();
    $stage=$this->request->getPathStage();
    $elements=array(
      'request'=>$this->request,
      'base_url'=>$this->request->baseUrl(),
      'stage_url'=>$stage,
      'session'=>$this->session
    );
    $view = new View($views_directory_path, $parameters);

    if($specified_path === null) {
      $path = $this->controller_name.'/'.$this->action_name;
    } else {
      $path = $specified_path;
    }
    $content = $view->render($path, $elements, $layout);
    return $content;
  }
  //CSRF対策
  protected function generateCsrfToken($name)
  {
    $token_key = 'csrf_token'.$name;
    $token = password_hash($token_key.microtime().session_id(), PASSWORD_DEFAULT);
    $tokens = $this->session->get($token_key,array());
    if (count($tokens) >= 5) {
      array_shift($tokens);
    }
    array_push($tokens,$token);
    $this->session->set($token_key, $tokens);
    return $token;
  }

  protected function checkCsrfToken($name,$posted_token)
  {
    $token_key = 'csrf_token'.$name;
    $tokens = $this->session->get($token_key,array());
    $key = array_search($posted_token, $tokens,true);
    if ($key !== false) {
      unset($tokens[$key]);
      $tokens = array_values($tokens);
      $this->session->set($token_key, $tokens);
      return true;
    }
    return false;
  }

  public function getKinds()
  {
    $diary_repository = $this->db_manager->getRepository('diary');
    $kinds = array();
    $feelings = $diary_repository->getKindsFeelings();
    $situations = $diary_repository->getKindsSituations();
    $devides = $diary_repository->getKindsDevides();
    $emotions = $diary_repository->getKindsEmotions();
    $kinds = array('feelings'=>$feelings,'situations'=>$situations,'devides'=>$devides,'emotions'=>$emotions);
    return $kinds;
  }
}
