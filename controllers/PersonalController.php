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

  public function indexAction()
  {

    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $user= $this->session->get('user');
    $parameters = array_merge($parameters,array('token'=>$token,'user'=>$user));
    return $content = $this->render($parameters);
  }

  public function editAction()
  {
    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $token = $this->generateCsrfToken('/personal/edit');

    $user= $this->session->get('user');
    $parameters = array_merge($parameters,array('token'=>$token,'name'=>$user['user_name'],'mail'=>$user['mail']));
    return $content = $this->render($parameters);
  }

  public function registerAction() //入力チェック&登録
  {
    //サインインされているかチェック
    //されていなければ'/signin'にリダイレクト
    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    //トークンのチェック
    if ($this->checkCsrfToken('/personal/edit', $this->request->getPost($posted_token)) !==true) {
      return $this->redirect('/personal/edit');
    }
    //入力内容チェック
    $name = $request->getPost('name');
    $mail = $request->getPost('mail');
    $new_password = $request->getPost('new_password');

    $user= $this->session->get('user');
    $user_repository = $this->db_manager->getRepository('user');

    //名前、メール、パス必須
    if (strlen($name)===0|| preg_match("/[^\s　]/",$name)) {
      $errors[] = '名前を入力してください';
    }
    if (strlen($mail)===0|| preg_match("/[^\s　]/",$mail)) {
      $errors[] = 'アドレスを入力してください。';
    } else if (!preg_match('/^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $mail)){
      $errors[]='半角英数字および記号は._-@のみ使用できます。';
    } else if (!preg_match($user['mail'],$mail) && $user_repository->countAccountByMail($mail)!==0) {
      $errors[]='希望されたメールアドレスは使用できません。';
    }

    //新パスが空の文字かつ確認パスが空の文字のとき(つまりパス変更は行わない)
    if ((strlen($password)===0|| preg_match("/[^\s　]/",$password))) {
      $errors[]='現在のパスワードを入力してください。変更には現在のパスワードが必要です';
    }

    //新パスまたは確認パスが空ではないとき
    if (!strlen($new_password)===0 || !preg_match("/[^\s　]/",$new_password) || !strlen($confirm_password)===0 || !preg_match("/[^\s　]/",$confirm_password)) {
      //新パスが入力されているとき
      if ((!strlen($new_password)===0 || !preg_match("/[^\s　]/",$new_password))  && (!preg_match('/[0-9a-z]/', $new_password))){
        $errors[]='パスワードには半角英数字のみ使用できます。';
      } else if (!(strlen($new_password)===0 || preg_match("/[^\s　]/",$new_password)) && (strlen($new_password) < 8 || strlen($new_password) > 16)) {
        $errors[] = '新しいパスワードは8字以上16字以下で入力してください。';
      }
      //新パス、確認パスが一致してないとき
      if ((!strlen($new_password)===0 || !preg_match("/[^\s　]/",$new_password) || !strlen($confirm_password)===0 || !preg_match("/[^\s　]/",$confirm_password)) && !preg_match($new_password,$confirm_password)) {
        $errors[] = '新しいパスワードと確認用パスワードが一致していません';
      }
    }

    if (!password_verify($password,$user['password'])) {
      $errors = 'パスワードに誤りがあります';
    }

    //不備あればerrorsに格納 '/personal/edit'にリダイレクト
    if(count($errors)!==0) {
      $parameters = array_merge($parameters,array('errors'=>$errors,'name'=>$name,'mail'=> $mail,'password'=>'','new_password'=>'','confirm_password'=>''));
      return $this->render($parameters,'/personal/edit', $layout = 'layout');
    } else {
      if (strlen($new_password)===0 || preg_match("/[^\s　]/",$new_password)) {
        $new_password = $password;
      } else {
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
      }

      //update
      $update_at= date("Y-m-d H:i:s");
      //$user['mail']と現在のパスワード$user['password']を元にアカウントを指定してupdate(user['id'],$password,$name,$mail,$new_password)
      $user_repository->updateUserInfo($name,$mail,$new_password,$update_at,$user['mail'],$user['password']);
      $personal = $user_repository->getUser($mail,$new_password);
      $this->session->set('user',$personal);
      return $this->redirect('/personal');
    }
  }

  public function intentAction($parameters) //削除の意向確認
  {
    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $token = $this->generateCsrfToken('/personal/intent');

    $user = $this->session->get('user');
    $parameters = array_merge($parameters,array('user'=>$user,'password'=>'','token'=>$token));
    return $this->render($parameters);

  }

  public function deleteAction($parameters) //アカウント削除
  {
    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }

    if ($this->checkCsrfToken('/personal/intent', $this->request->getPost($posted_token)) !==true) {
      return $this->redirect('/personal/intent');
    }

    $user = $this->session->get('user');
    if (!password_verify($password,$user['password'])) {
      $errors = 'パスワードに誤りがあります';
      $parameters = array_merge($parameters,array('errors'=>$errors,'user'=>$user,'password'=>'','token'=>$token));
      return $this->render($parameters,'/personal/intent',$layout = 'layout');
    }

    //アップデートでdeleteフラグを1にする。delete_atに時刻を.
    $now = date("Y/m/d H:i:s");
    $user_repository = $this->db_manager->getRepository('user');
    $user = $this->session->get('user');

    $user_repository->deleteAccount($user['mail'],$user['password'],$now);

    $diary_repository = $db_manager->getRepository('diary');
    $diary_repository->deleteDiary($user['id'],$now);
    $diary_repository->deleteContents($user['id'],$now);


    return $this->redirect('/account/signin');
  }
}
