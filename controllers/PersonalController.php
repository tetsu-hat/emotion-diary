<?php
class PersonalController extends Controller
{
  protected $controller_actions = array(
    'index',
    'edit',
    'register',
    'intent',
    'delete'
  );

  public function indexAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $user = $this->session->get('user');
    $parameters = array_merge($parameters,array('user'=>$user));
    return $content = $this->render($parameters);
  }

  public function editAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $token = $this->generateCsrfToken('/personal/edit');
    $user = $this->session->get('user');
    $parameters = array_merge($parameters,array('token'=>$token,'user'=>$user,'name'=>$user['name'],'mail'=>$user['mail'],'sex'=>$user['sex_id']));

    return $content = $this->render($parameters);
  }

  public function registerAction($parameters)
  {
    //サインインされていなければ'/signin'にリダイレクト
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    //トークンのチェック
    if ($this->checkCsrfToken('/personal/edit', $this->request->getPost('csrf_token')) !== true) {
      return $this->redirect('/personal/edit');
    }

    $user = $this->session->get('user');
    $name = $this->request->getPost('name');
    $mail = $this->request->getPost('mail');
    $sex = $this->request->getPost('sex');
    $password = $this->request->getPost('password');
    $new_password = $this->request->getPost('new_password');
    $confirm_password = $this->request->getPost('confirm_password');

    $user_repository = $this->db_manager->getRepository('user');
    $count_account = $user_repository->countAccountByMail($mail);
    $token = $this->generateCsrfToken('/personal/edit');
    $errors=array();

    $errors=$this->check->checkPersonalInput($name,$mail,$sex,$password,$new_password,$confirm_password,$parameters['action']);
    if($this->check->checkSex($sex)!== null){
      $sex="2";
    }
    //不備あればerrorsに格納 '/personal/edit'にリダイレクト
    if(count($errors)!== 0) {
      $parameters = array_merge($parameters,array('errors'=>$errors,'user'=>$user,'name'=>$name,'mail'=>$mail,'sex'=>$sex,'password'=>'','new_password'=>'','confirm_password'=>'','token'=>$token));
      return $this->render($parameters,'/personal/edit', $layout = 'layout');
    } else {
      if (strlen($new_password) === 0) {
        $new_password = $user['password'];
      } else {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
      }
      $update_at = date("Y-m-d H:i:s");
      $user_repository->updateUser($name,$mail,$sex,$new_password,$update_at,$user['mail'],$user['password']);
      $personal = $user_repository->getUser($mail);
      $this->session->set('user',$personal);
      return $this->redirect('/personal');
    }
  }
  //削除の意向確認
  public function intentAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $token = $this->generateCsrfToken('/personal/intent');
    $user = $this->session->get('user');
    $errors=array();
    $parameters = array_merge($parameters,array('user'=>$user,'password'=>'','token'=>$token));
    return $this->render($parameters);

  }
  //アカウント、日記、日記ごとの内容を削除
  public function deleteAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    if ($this->checkCsrfToken('/personal/intent', $this->request->getPost('csrf_token')) !==true) {
      return $this->redirect('/personal/intent');
    }
    $user = $this->session->get('user');
    $password = $this->request->getPost('password');
    $token = $this->generateCsrfToken('/personal/intent');
    $errors = array();
    $errors = $this->check->checkDeleteAccount($password,$user['password']);
    if(count($errors)!==0){
      $parameters = array_merge($parameters,array('errors'=>$errors,'user'=>$user,'password'=>'','token'=>$token));
      return $this->render($parameters,'/personal/intent',$layout = 'layout');
    }
    //アップデートでdeleteフラグを1にする。delete_atに時刻を.
    date_default_timezone_set('Asia/Tokyo');
    $now = date("Y/m/d H:i:s");
    $user_repository = $this->db_manager->getRepository('user');
    $diary_repository = $this->db_manager->getRepository('diary');
    $user = $this->session->get('user');

    $diary_repository->deleteContents($user['id'],$now);
    $diary_repository->deleteDiary($user['id'],$now);
    $user_repository->deleteAccount($user['mail'],$user['password'],$now);
    $this->session->clear();
    $this->session->set('_authenticated',false);
    return $this->redirect('/account/signin');
  }
}
