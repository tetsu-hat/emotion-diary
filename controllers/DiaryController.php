<?php
class DiaryController extends Controller
{
  protected $controller_actions = array(
    'index',
    'edit',
    'register',
  );

  //閏年チェックを行うメソッド
  public function cheackLeapYear($request_date) {
    //閏年判定
    $year = date("Y",strtotime($request_date));
    if ($year/4===0 && $year/100!==0 && $year/400===0) {
      return true; //閏年
    }
    return false;
  }

  //存在する日なのかチェックするメソッド
  public function checkBeDate($request_date) {
    //要求された日付が空ではないかチェック
    if(empty($request_date)) {
      return false;
    }
    //要求された日付が空ではなく形式がY-m-dであるかチェック
    if (!empty($request_date) && !preg_match('/^[0-9]{4}-([0-1]{1}[0-2]{1})-([0-2]{1}[0-9]{1}|3[0-1]{1})$/', $request_date)) {
      return false;
    }
    //以下、各月の日数のチェック
    //30日までの月、31日までの月、2月それぞれ
    $today = date("Y-m-d");
    $year = date('Y', strtotime($request_date));
    $month = date('m', strtotime($request_date));
    $year_month = $year.'-'.$month.'-';
    //30日まで4,6,9,11
    if ($month === '04' || $month ==='06' || $month ==='09' || $month === '11') {
      if (strtotime($year_month.'01') <=strtotime($request_date) && strtotime($request_date) < strtotime($year_month.'31')) {
        //要求された日付が今日より未来であるかチェック
        if (strtotime($request_date) > strtotime($today)) {
          return false;
        }
      }
    }
    //31日まで
    if ($month === '01' || $month ==='03' || $month ==='05' || $month ==='07' || $month ==='08' || $month ==='10' ||$month ==='12') {
      if (strtotime($year_month.'01') <=strtotime($request_date) && strtotime($request_date) <= strtotime($year_month.'31')) {
        //要求された日付が今日より未来であるかチェック
        if (strtotime($request_date) > strtotime($today)) {
          return false;
        }
      }
    }
    //2月判定
    if ($month === '02') {
      //閏年
      if ($this->cheackLeapYear($request_date)) {
        if (strtotime($year_month.'01') <=strtotime($request_date) && strtotime($request_date) <= strtotime($year_month.'29')) {
          //要求された日付が今日より未来であるかチェック
          if (strtotime($request_date) > strtotime($today)) {
            return false;
          }
        }
      }
      //閏年ではないとき
      if (strtotime($year_month.'01') <=strtotime($request_date) && strtotime($request_date) <= strtotime($year_month.'28')) {
        //要求された日付が今日より未来であるかチェック
        if (strtotime($request_date) > strtotime($today)) {
          return false;
        }
      }
    }
    //いずれにも該当しなかったとき
    return true;
  }

  public function getElement($user_id, $request_date) {
    //リポジトリの決定
    $diary_repository = $this->db_manager->getRepository('diary');
    //日記id等の取得
    $diary = $diary_repository->getDiary($user_id,$request_date);
    //日記id等の有無を確認
    if (empty($diary)) {
      $diary['feeling'] = 0;
      $diary['picture_url'] = '';
      for($i=0;$i++;$i<5) {
        $contents[$i] = '';
        $emotions[$i] = 0;
        $situation[$i] = 0;
        $date_contents[$i] = array('contents'=>$contents[$i],'emotinos'=>$emotions[$i],'situations'=>$situetion[$i]);
      }

    } else {
      //コンテンツ内容の取得
      $date_contents = $diary_repository->getContents($diary['id']);
      //日記id等はあったがコンテンツ内容がなかった時
      if (empty($date_contents)) {
        for($i=0;$i++;$i<5) {
          $contents[$i] = '';
          $emotions[$i] = 0;
          $situation[$i] = 0;
          $date_contents[$i] = array('contents'=>$contents[$i],'emotinos'=>$emotions[$i],'situations'=>$situetion[$i]);
        }
      }
      //日記id等、コンテンツ内容共にあったときは特に処理なし

    }
    //取得内容を$valuesに格納してreturn;
    return $values = array(
      'date'=>$request_date,
      'dairy'=>$diary,
      'date_contents'=>$date_contents
    );
  }


  public function indexAction($parameters)
  {
    //サインイン中か確認
    //サインインしていなかったら'/signin'にリダイレクト
    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }

    $user = $this->session->get('user');
    $today = date("Y-m-d");
    //日付が指定されているか確認
    $request_year = $this->request->getPost('year');
    $request_month = $this->request->getPost('month');
    $request_day = $this->request->getPost('day');
    $request_date = $today;
    $select_year_period = date("Y",strtotime($today))-date("Y",strtotime($user['create_at']));
    $select_year = array(
                          'first'=>date("Y",strtotime($user['create_at'])),
                          'last'=>date("Y",strtotime($today)),
                          'period'=>$select_year_period
                        );

    if (isset($request_year) && isset($request_month) && isset($request_day)) {
      $request_date = $request_year.'-'.$request_month.'-'.$request_day;
    }
    if (isset($parameters['date'])) {
      $request_date = $parameters['date'];
    }

    if ($this->checkBeDate($request_date) ===false ) {
      $errors[]='要求された日付については表示できないため、今日の日付を表示しています';
      $request_date = $today;
    }

    if (count($errors)!==0) {
        //日記を構成するデータの取得 getElement()

        $values = $this->getElement($user['id'], $request_date);
        //そして$errorsと$userをarray_merge()して$parametersとarray_merge()
        $varues = array_merge($values,array('errors'=>$errors,'date'=>$request_date,'user'=>$user,'select_year'=>$select_year));

      $parameters = array_merge($parameters,$values);
      //日付を指定してrender()
      return $this->render($parameters,'/diary/index'.$request_date);
    }

    //日記を構成するデータの取得 getElement()
    $values = $this->getElement($user['id'], $request_date);
    $values = array_merge($values,array('date'=>$request_date,'user'=>$user));
    $parameters = array_merge($parameters,$values);
    //render() //テキストボックスやセレクトに値を格納して表示
    return $this->render($parameters,'/diary/index/'.$request_date);
  }

  public function editAction($parameters)
  {
    //サインイン中か確認
    //サインインしていなかったら'/signin'にリダイレクト
    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    //トークン生成
    $token = $this->generateCsrfToken('/diary/edit');

    $request_date = $parameters['date'];
    $today = date("Y-m-d");

    $diary_repository = $this->db_manager->get('diary');

    $kinds_situations = $diary_repository->getKindsSituations();
    $kinds_emotions = $diary_repository->getKindsEmotions();

    $parameters = array_merge($parameters, array('kinds_situations'=>$kinds_situations,'kinds_emotions'=>$kinds_emotions));

    if ($this->checkBeDate($request_date) ===false ) {
      $errors[]='要求された日付について編集ページを表示できないため、今日の日記閲覧ページを表示しています';
      $request_date = $today;
    }

    if (count($errors)!==0) {
      //日記を構成するデータの取得 getElement()
      $values = $this->getElement($user['id'], $request_date);
      //そして$errorsと$userをarray_merge()して$parametersとarray_merge()
      $varues = array_merge($values,array('errors'=>$errors,'date'=>$request_date,'user'=>$user));

      $parameters = array_merge($parameters,$values);
      //日付を指定してrender()
      return $this->render($parameters,'/diary/index'.$request_date);
    }
    //日記を構成するデータの取得 getElement()
    $values = $this->getElement($user['id'], $request_date);
    $value = array_merge($value, array('user'=>$user));
    $parameters = array_merge($parameters,$values);
    //render() //テキストボックスやセレクトに値を格納して表示
    return $this->render($parameters,'/diary/edit'.$request_date);
  }

  public function registerAction($parameters)
  {
    //サインイン中か確認
    //サインインしていなかったら'/signin'にリダイレクト
    if (!$this->isAuthenticated()) {
      $this->redirect('/account/signin');
    }

    $date = $this->request->getPost('date');
    $today = date("Y-m-d");
    $user = $this->session->get('user');
    //リクエストされた日付が有効かチェック
    //されていなかったら今日の日付を取得
    if ($this->checkBeDate($request_date) ===false ) {
      $errors[]=' 要求された日付について不正な操作があったため、今日の日付で日記を表示しています';
      $date = $today;
    }

    if (count($errors) !== 0) {
      //日記を構成するデータの取得 getElement()
      $values = $this->getElement($user['id'], $date);
      $values = array_merge($values,array('errors'=>$errors,'date'=>$date, 'user'=>$user));
      //そして$errorsと$userをarray_merge()して$parametersとarray_merge()
      $parameters = array_merge($parameters,$values);

      return $this->render($parameters,'/diary/index'.$date);
    }

    //トークンが適合するか確認
    if ($this->checkCsrfToken('/diary/edit', $this->request->getPost('csrf_token')) !==true) {

      return $this->redirect('/diary/index'.$date);
    }

    $feeling = $this->request->getPost('feeling');
    $picture_path = $this->request->getPost('picture');

    for ($i=0;$i++;$i<5) {
      $situation[$i] = $this->request->getPost('situation_'.$i);
      $emotions[$i] = $this->request->getPost('emotion_'.$i);
      $contents[$i] = $this->request->getPost('content_'.$i);
    }
    //入力内容のチェック
    //エラーがある場合はerrors,字数140以内
    for($i=0;$i++;$i<5) {
      if (strlen($contents[$i])===0|| preg_match("/[^\s　]/",$contents[$i])) {
        $contents[$i]='';
      }
    }

    //for文if文で削除。削除unset();
    for($i=0;$i++;$i<5) {
      if($contents[$i]===''){
        unset($contents[$i],$emotions[$i],$situation[$i]);
        return true;
      }
    }

    if($contents ===array()) {
      return  $this->redirect('/diary/index'.$date);
    }
    //日記の内容は上から順に0~4の番号を振る(詰めるarray_values())
    $contents = array_values($contents);
    $emotions = array_values($emotions);
    $situation = array_values($situation);
    //文字数が140字以下かチェック
    $count = count($contents);
    for ($i=0;$i++;$i<$count) {
      if (strlen($contents[$i]) > 140)  {
        $errors[] = ($i+1).'番目の日記の内容は140字以内で入力してください。';
      }
    }

    if (count($errors) !== 0) {
      //日記を構成するデータの取得 getElement()
      $values = $this->getElement($user['id'], $date);
      $values = array_merge($values,array('errors'=>$errors,'date'=>$date, 'user'=>$user));
      //そして$errorsと$userをarray_merge()して$parametersとarray_merge()
      $parameters = array_merge($parameters,$values);
      return $this->render($parameters,'/diary/edit'.$date);
    }
    //リポジトリの決定
    $diary_repository = $this->db_manager->getRepository('diary');
    //データベースにインサート
    $now = date("Y-m-d H:i:s");
    $user = $this->session->get('user');

    $count_that_day_diary = $diary_repository->countThatDiary($user['id'],$date);
  //当日の日記のidがなければinsert、あればupdate
    if ($count_that_day_diary===1) {
      $diary = $diary_repository->getDiary($user['id'],$date);
      $diary_repository->updateDiary($feeling,$picture_path,$now,$diary['id']);
      for($i=0;$i++;$i<$count) {
        $diary_repository->updateContents($situation[$i],$emotions[$i],$content[$i],$now,$diary['id'],$i);
      }
    } else if ($count_that_day_diary===0) {
      $diary_repository->insertDiary($user['id'],$date,$feeling,$picture_path,$now);
      $diary = $diary_repository->getDiary($user['id'],$date);
      for($i=0;$i++;$i<$count) {
        $diary_repository->insertContents($user['id'],$diary['id'],$situation[$i],$emotions[$i],$content[$i],$now,$i);
      }
    }
    return  $this->redirect('/diary/index'.$date);
  }

}
