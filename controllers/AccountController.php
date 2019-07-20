<?php
class AccountController extends Controller
{
  protected $controller_actions = array(
    'index',
    'signin',
    'authenticate',
    'signup',
    'register',
    'signout',
    'emotions'
  );

  public function indexAction($parameters)
  {
    if (!$this->isAuthenticated()) {
      return  $this->redirect('/account/signin');
    }

    $user = $this->session->get('user');
    //リポジトリの決定
    $repository = $this->db_manager->getRepository('diary');
    $count_page = $this->repositories['diary']->countpage($user_id);//日記の日数の取得
    $count_contents = $this->repositories['diary']->countContents($user_id);
    $today = date("Y-m-d");
    $select_year_period = date("Y",strtotime($today))-date("Y",strtotime($user['create_at']));
    $select_year = array(
                          'first'=>date("Y",strtotime($user['create_at'])),
                          'last'=>date("Y",strtotime($today)),
                          'period'=>$select_year_period
                        );
  //日記の記事数の取得

    for($i=0;$i++;$i<3) {
    $count_emotions_devides[$i] = $this->repositories['diary']->countDevides($user['id'],$i);    //感情のポジネガ他の数を取得
    $percentage_emotions_devides[$i] = $count_emotions_devides[$i]/$count_contents*100;
  }
    $values = array(
      'count_page'=>$count_page,
      'count_contents' =>$count_contents,
      'count_emotions_devides' =>$count_emotions_devides,
      'percentage_emotions_devides' => $percentage_emotions_devides,
      'user'=>$user,
      'select_year'=>$select_year
    );
    //取得した値をパラメータにマージ
    $parameters = array_merge($parameters,$values);
    return $this->render($parameters);
  }
  //サインインページ表示
  public function signinAction()
  {
    if ($this->isAuthenticated()) {
      return  $this->redirect('/');
    }
    //トークン作成
    $token = $this->generateCsrfToken('/account/signin');
    $parameters = array_merge($parameters,array('token'=>$token,'name'=>'', 'mail'=>'','password'=>''));
    return $content = $this->render($parameters);
  }
  //サインインページ入力内容チェックおよびリダイレクト
  public function authenticateAction($parameters)
  {
    if ($this->isAuthenticated()) {
      return $this->redirect('/');
    }
    //トークンが適合するか確認
    if ($this->checkCsrfToken('/account/signin', $this->request->getPost('csrf_token')) !==true) {
      return $this->redirect('/account/signin');
    }

    $mail = $request->getPost('mail');
    $password = $request->getPost('password');
    if (strlen($mail)===0|| preg_match("/[^\s　]/",$mail)) {
      $errors[] = 'アドレスを入力してください。';
    }
    if (strlen($password)===0|| preg_match("/[^\s　]/",$password)) {
      $errors[] = 'パスワードを入力してください。';
    }
    if (count($errors) !== 0) {
      $parameters = array_merge($parameters, array('errors'=>$errors,'name'=>$name, 'mail'=>$mail,'password'=>''));
      return $content = $this->render($parameters, '/account/signin', $layout = 'layout');
    } else if (count($errors) === 0) {

      $user_repository = $this->$db_manager->getRepository('user');
      $user = $user_repository->getUser($mail);
      if ($user['mail']!==$mail || !password_verify($password,$user['password'])) {
        $errors[]='入力内容に誤りがあります';
        $parameters = array_merge($parameters, array('errors'=>$errors));
        return $content = $this->render($parameters, '/account/signin');
      }

      $this->session->set('user', $user);
      return $this->redirect('/');
    }

  }
  //新規登録ページ表示
  public function signupAction($parameters) {
    if ($this->isAuthenticated()) {
      $this->redirect('/');
    }
    //トークン作成
    $token = $this->generateCsrfToken('account/signup');
    $parameters = array_merge($parameters,array('name'=>'','mail'=>'','password'=>'','confirm_password'=>'','token'=>$token));
    return $content = $this->render($parameters);
  }

  //入力内容チェックかつリダイレクトもしくはレンダリング
  public function registerAction($parameters)
  {
    if ($this->isAuthenticated()) {
      $this->redirect('/');
    }
    //トークンチェック
    //トークンが適合するか確認
    if ($this->checkCsrfToken('/account/signin', $this->request->getPost('csrf_token')) !==true) {
      return $this->redirect('/account/signin');
    }

    $name = $this->$request->getPost('name');
    $mail = $this->$request->getPost('mail');
    $password = $this->$request->getPost('password');
    $confirm_password = $this->$request->getPost('confirm_password');
    $token = $this->$request->getPost('csrf_token');

    if (strlen($mail)===0|| preg_match("/[^\s　]/",$name)) {
      $errors[] = '名前を入力してください';
    }

    if (strlen($mail)===0|| preg_match("/[^\s　]/",$mail)) {
      $errors[] = 'アドレスを入力してください。';
    } else if (!preg_match('/^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $mail)){
      $errors[]='半角英数字および記号は._-@のみ使用できます。';
    }

    if (strlen($password)===0|| preg_match("/[^\s　]/",$password)) {
      $errors[] = 'パスワードを入力してください。';
    } else if (strlen($password) < 8 || strlen($password) > 16)  {
      $errors[] = 'パスワードは8字以上16字以下で入力してください。';
    } else if (!preg_match('/[0-9a-z]/', $password)){
      $errors[]='半角英数字のみ使用できます。';
    } else if ($password !== $$confirm_password) {
      $errors[] = '確認用メールアドレスに誤りがあります。';
    }

    if (count($errors) !== 0) {
      $parameters = array_merge($parameters, array('errors'=>$errors,'name'=>$name,'$mail'=>$mail,'$password'=>'','token'=>$token));
      return $content = $this->render($parameters, '/account/signup', $layout = 'layout');
    } else if (count($errors) === 0) {

      $user_repository = $this->$db_manager->getRepository('user');
      $count_account = $user_repository->countAccountByMail($mail);
      if ($count_account !==0) {
        $errors[]='入力されたメールアドレスはすでに登録されています。';
        $parameters = array_merge($parameters, array('errors'=>$errors,'name'=>$name, 'mail'=>$mail,'password'=>'','confirm_password'=>'','token'=>$token));
        return $content = $this->render($parameters, '/account/signup', $layout = 'layout');
      }

      $password = password_hash($password, PASSWORD_DEFAULT);
      $create_at= date("Y-m-d H:i:s");
      $user_repository->insertUser($name,$mail,$password,$create_at);
      $user = $user_repository->getUser($mail, $password);
      $this->session->set('user', $user);
      return $this->redirect('/');

    }
  }

  public function signoutAction(){
    if ($this->isAuthenticated()) {
      $this->redirect('/');
    }
    $this->session->clear();

    $this->session->set('_authenticated',false);

    $this->redirect('/');
  }

  public function emotionsAction($parameters)
  {
    if ($this->isAuthenticated()) {
      $this->redirect('/');
    }

    $user = $this->session->get('user');
    $request_period = $this->request->get('period');

    $values = $this->getCountEmotionsAndDevides($user_id,$request_period);
    $parameters = array_merge($parameters,$values);
    $parameters = array_merge($parameters,array('user'=>$user));

    return $this->render($parameters);
  }

  //今まで書いた日記の感情や感情の大分類の合計
  public function getCountEmotionsAndDevides($user_id,$period){


    $diary_repository = $this->db_manager->get('diary');
    $today = date("Y-m-d");

    if (isset($period) && $period==='month') {
      //今月、先月
      $period_before['first'] = date("Y-m-1",strtotime("-2 month"));
      $period_before['last'] = date("Y-m-1",strtotime("-1 month"));
      $period_before['name'] = '先月';

      $period_after['first'] = date("Y-m-1",strtotime("-1 month"));
      $period_after['last'] = date("Y-m-d",strtotime("+1 day"));
      $period_after ['name']= '今月';
    }else if (isset($period) && $period==='half_year') {
      //今月、先月
      $period_before['first'] = date("Y-m-d",strtotime("-1 year"));
      $period_before['last'] = date("Y-m-d",strtotime("-6 week"));
      $period_before['name'] = '前期';

      $period_after['first'] = date("Y-m-d",strtotime("-6 week"));
      $period_after['last'] = date("Y-m-d",strtotime("+1 day"));
      $period_after ['name']= '後期';
    }else {
      // 週間でリクエストもしくは失敗した時
      $period_before['first'] = date("Y-m-d",strtotime("-2 week"));
      $period_before['last'] = date("Y-m-d",strtotime("-1 week"));
      $period_before['name'] = '先週';

      $period_after['first'] = date("Y-m-d",strtotime("-1 week"));
      $period_after['last'] = date("Y-m-d",strtotime("+1 day"));
      $period_after ['name']= '今週';
    }

    for($i=0;$i++;$i<3) {
      $total_count_devides[$i] = $this->repositories['diary']->countDevides($user['id'],$i);    //感情のポジネガ他の数を取得
      $percentage_total_count_devides[$i] = $count_emotions_devides[$i]/$count_contents*100;
    }

    for($j=0;$j++;$j<41) {
      $count_emotions['after'][$j] = $diary_repository->countEmotion($user['id'],$j,$period_after['first'],$period_after['last']);
      $count_emotions['before'][$j] = $diary_repository->countEmotion($user['id'],$j,$period_before['first'],$period_before['last']);
    }

    //devides=0,1,2をそれぞれ抽出する
    for($i=0;$i++;$i<3) {
      $emotions_devides['after'][$i] = array_filter($count_emotions['after'], function($row){
        return $row['devide_id'] == $i;
      });
      $emotions_devides['before'][$i] = array_filter($count_emotions['before'], function($row){
        return $row['devide_id'] == $i;
      });

      //抽出したものの配列を詰める
      $emotions_devides['after'][$i]= array_values($emotions_devides['after']);
      $emotions_devides['before'][$i]= array_values($emotions_devides['before']);

      $total_count['after'][$i]=array_sum($emotions_devides['after'][$i]);
      $total_count['before'][$i]=array_sum($emotions_devides['before'][$i]);

      $devides = array('ポジティヴ','ネガティヴ','その他');

      //ポジティヴ、ネガティブ、その他ごとの感情の種類の数
      $count_devides[$i]['count']=count($emotions_devides['after'][$i]);
      $count_devides[$i]['name']=$devides[$i];
    }


  return  $values = array(
      'total_count_devides'=>$total_count_devides,
      'percentage_total_count_devides'=>$percentage_total_count_devides,
      'period_before'=>$period_before,
      'period_after'=>$period_after,
      'count_devides'=>$count_devides,
      'count_after'=>$emotions_devides['after'],
      'count_before'=>$emotions_devides['before'],
      'total_after'=>$total_count['after'],
      'total_before'=>$total_count['before']
    );

  }



}
