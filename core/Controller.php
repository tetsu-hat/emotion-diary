<?php
abstract class Controller
{
  //使用するインスタンスなどを格納するプロパティ定義
  protected $application;
  protected $request;
  protected $db_manager;
  protected $session;
  protected $response;
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
  }
  //hogeControllerのアクションの実行と出力内容を返す処理
  public function run($action,$parameters)
  {
    $this->controller_name = lcfirst(rtrim(get_class($this), 'Controller'));
    $this->action_name = $action;
    $this->action_method = $action.'Action';
    //メソッドが存在するか確認。しなければmove404へ
    if(!$this->isAction($action_name,$controller_actions)) {
      return  $this->move404();
    };
    //メソッドが存在したけれどサインインしていない場合
    if($this->isAction($action_name,$controller_actions) && !$this->session->isAuthenticated()) {
      throw new UnauthenticatedActionException();
    }
    $content = $this->action_method($parameters);

    $this->response->store($content);
  }
  //リクエストされたアクションが存在するか配列より確認
  public function isAction($action,$controller_actions)
  {
    if (array_search($action, $controller_actions) !==false && in_array($action, $controller_actions) === true) {
      return true;
    }
    return false;
  }

  //要求されたファイルが存在しないとき404の例外を投げる
  public function move404()
  {
    throw new HttpNotFoundException('Not route found'.$this->controller_name.'/'.$this->action_name);
  }

  //指定されたurlへリダイレクト
  public function redirect($path)
  {
    $base_url = $this->request->baseUrl();
    $redirect_url = $base_url.$path; //引数のパスを元にファイルパスを組み立てる
    if ($this->request->isSsl()) {
      $url = 'https://' .$redirect_url;
    } else {
      $url = 'http://' .$redirect_url;
    };
    //ステータスコード302、テキスト Foundを変更してResponseクラスのプロパティに格納
    $this->response->setStatusCode(302, 'Found');
    //Responseクラスの$redirect_headersにurlを格納
    $this->response->setRedirectHeaders($name, $url);
  }
  //viewクラスをインスタンス化して見た目を構成していく
  public function render($parameters, $specified_path = null, $layout = 'layout')
  {
    $views_directory_path = $this->application-> getViwesDir();

    $elements=array(
      'request'=>$this->request,
      'session'=>$this->session
    );

      $view = new View($views_directory_path, $parameters);

      if($specified_path = null) {
        $path = $this->controller_name.'/'.$this->action_name;
      } else {
          $path = $this->controller_name.'/'.$specified_path;
      }
      $content = $view->render($path, $parameters, $layout);
      return $content;
    }

  }
