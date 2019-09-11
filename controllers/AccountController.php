<?php

class AccountController extends Controller
{
  protected $controller_actions = array(
    'index',
    'signout',
    'emotions'
  );

  public function indexAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      return  $this->redirect('/account/signin');
    }
    $user = $this->session->get('user');
    //リポジトリの決定
    $diary_repository = $this->db_manager->getRepository('diary');
    $count_days = $diary_repository->countDays($user['id']);//日記の日数の取得
    $count_contents = $diary_repository->countContents($user['id']);
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    $select_year_period = date("Y",strtotime($today))-date("Y",strtotime($user['create_at']));
    if($select_year_period<1){
      $select_year_period = 1;
    }
    $select_year = array(
      'first'=>date("Y",strtotime($user['create_at'])),
      'last'=>date("Y",strtotime($today)),
      'period'=>$select_year_period
    );
    $emotion_devides = $diary_repository->getKindsDevides();
    //日記の記事数の取得
    for($i=0;$i<3;$i++) {
      $count_emotions_devides[$i] = $diary_repository->countDevides($user['id'],$i);    //感情のポジネガ他の数を取得
    }
    //感情の大まかに分けた名称取得
    $values = array(
      'today'=>$today,
      'count_days'=>$count_days,
      'count_contents' =>$count_contents,
      'emotion_devides' =>$emotion_devides,
      'count_emotions_devides' =>$count_emotions_devides,
      'user'=>$user,
      'select_year'=>$select_year,
    );
    //取得した値をパラメータにマージ
    $parameters = array_merge($parameters,$values);
    return $this->render($parameters);
  }
  //サインインページ表示
  public function signinAction($parameters)
  {
    if ($this->session->isAuthenticated()) {
      return  $this->redirect('/');
    }
    //トークン作成
    $token = $this->generateCsrfToken('/account/signin');
    $parameters = array_merge($parameters,array('token'=>$token,'mail'=>'','password'=>''));
    return $content = $this->render($parameters);
  }
  //サインインページ入力内容チェックおよびリダイレクト
  public function authenticateAction($parameters)
  {
    if ($this->session->isAuthenticated()) {
      return $this->redirect('/');
    }
    //トークンが適合するか確認
    if ($this->checkCsrfToken('/account/signin', $this->request->getPost('csrf_token')) !==true) {
      return $this->redirect('/account/signin');
    }
    $mail = $this->request->getPost('mail');
    $password = $this->request->getPost('password');
    $token = $this->generateCsrfToken('/account/signin');
    $errors=array();
    $errors=$this->check->checkSigninInput($mail,$password,$parameters['action']);
    if (count($errors) !== 0) {
      $parameters = array_merge($parameters, array('errors'=>$errors,'mail'=>$mail,'password'=>'','token'=>$token));
      return $content = $this->render($parameters, '/account/signin', $layout = 'layout');
    } else if (count($errors) === 0) {
      $user_repository = $this->db_manager->getRepository('user');
      $count_account= $user_repository->countAccountByMail($mail);
      $user = $user_repository->getUser($mail);
      if ($count_account['get_value'] !=='1') {
        $errors[]='入力内容に誤りがあります';
      }else if ($user['mail']!==$mail || !password_verify($password,$user['password'])) {
        $errors[]='入力内容に誤りがあります';
      }
      if (count($errors) !== 0) {
        $parameters = array_merge($parameters, array('errors'=>$errors,'mail'=>$mail,'password'=>'','token'=>$token));
        return $content = $this->render($parameters, '/account/signin');
      }
      $this->session->set('user', $user);
      $this->session->setAuthenticated(true);
      return $this->redirect('/');
    }
  }
  //新規登録ページ表示
  public function signupAction($parameters)
  {
    if ($this->session->isAuthenticated()) {
      $this->redirect('/');
    }
    //トークン作成
    $token = $this->generateCsrfToken('/account/signup');
    $parameters = array_merge($parameters,array('name'=>'','mail'=>'','password'=>'','confirm_password'=>'','token'=>$token));
    return $content = $this->render($parameters);
  }
  //登録
  public function registerAction($parameters)
  {
    if ($this->session->isAuthenticated()) {
      return $this->redirect('/');
    }
    //トークンチェック
    if ($this->checkCsrfToken('/account/signup', $this->request->getPost('csrf_token')) !==true) {
      return $this->redirect('/account/signin');
    }
    $name = $this->request->getPost('name');
    $mail = $this->request->getPost('mail');
    $password = $this->request->getPost('password');
    $confirm_password = $this->request->getPost('confirm_password');
    $token = $this->generateCsrfToken('/account/signup');
    $errors=array();
    $errors=$this->check->checkSignupInput($name,$mail,$password,$confirm_password,$parameters['action']);
    if (count($errors) !== 0) {
      $parameters = array_merge($parameters, array('errors'=>$errors,'name'=>$name,'mail'=>$mail,'password'=>'','confirm_password'=>'','token'=>$token));
      return $content = $this->render($parameters, '/account/signup', $layout = 'layout');
    } else if (count($errors) === 0) {
      $user_repository = $this->db_manager->getRepository('user');
      $password = password_hash($password, PASSWORD_DEFAULT);
      $create_at= date("Y-m-d H:i:s");
      $user_repository->insertUser($name,$mail,$password,$create_at);
      $user = $user_repository->getUser($mail, $password);
      $this->session->set('user', $user);
      $this->session->setAuthenticated(true);
      return $this->redirect('/');
    }
  }

  public function signoutAction()
  {
    if ($this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $this->session->clear();
    $this->session->set('_authenticated',false);
    return $this->redirect('/account/signin');
  }

  public function emotionsAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      return  $this->redirect('/account/signin');
    }
    $user = $this->session->get('user');
    $request_period = $this->request->getPost('period');
    if(!isset($request_period)){
      $request_period='week';
    }
    $kinds=$this->getKinds();
    $by_period = new ByPeriod($this->application);
    $values = $by_period->getCountEmotionsAndDevides($user['id'],$request_period,$kinds);
    $parameters = array_merge($parameters,$values,array('user'=>$user,'request_period'=>$request_period));
    return $this->render($parameters);
  }
}
