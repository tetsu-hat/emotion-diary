<?php
class DiaryController extends Controller
{
  protected $controller_actions = array(
    'index',
    'edit',
    'register',
  );

  public function indexAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    $user = $this->session->get('user');
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    //日付が指定されているか確認
    $request_year = $this->request->getPost('year');
    $request_month = $this->request->getPost('month');
    $request_day = $this->request->getPost('day');
    $select_year_period = date("Y",strtotime($today))-date("Y",strtotime($user['create_at']));
    $select_year = array(
      'first'=>date("Y",strtotime($user['create_at'])),
      'last'=>date("Y",strtotime($today)),
      'period'=>$select_year_period
    );
    $errors = array();
    if (isset($request_year) && isset($request_month) && isset($request_day)) {
      $request_date =date("Y-m-d",strtotime($request_year.'-'.$request_month.'-'.$request_day));
    }else if (isset($parameters['date'])) {
      $request_date = $parameters['date'];
    }else{
      $request_date = $today;
    }
    if ($this->check->checkBeDate($request_date) ===false ) {
      $errors[]='要求された日付については表示できないため、今日の日付を表示しています';
      $request_date = $today;
    }
    if (count($errors)!==0) {
      $parameters = array_merge($parameters,$this->getElement($user['id'], $request_date),array('errors'=>$errors,'date'=>$request_date,'user'=>$user,'select_year'=>$select_year));
      return $this->render($parameters);
    }
    $parameters = array_merge($parameters,$this->getElement($user['id'], $request_date),array('date'=>$request_date,'user'=>$user,'select_year'=>$select_year));
    return $this->render($parameters,'/diary/index');
  }

  public function editAction($parameters)
  {
    //サインインしていなかったら'/signin'にリダイレクト
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    //トークン生成
    $token = $this->generateCsrfToken('/diary/edit');
    $request_date = $parameters['date'];
    $user = $this->session->get('user');
    $errors=array();
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    if ($this->check->checkBeDate($request_date) ===false ) {
      $errors[]='要求された日付については表示できないため、今日の日付を表示しています';
      $request_date = $today;
    }
    $parameters = array_merge($parameters,$this->getElement($user['id'], $request_date),array('errors'=>$errors,'user'=>$user,'token'=>$token));
    if (count($errors)!==0) {
      return $this->render($parameters,'/diary/index');
    }
    return $this->render($parameters,'/diary/edit');
  }

  public function registerAction($parameters)
  {
    if (!$this->session->isAuthenticated()) {
      $this->redirect('/account/signin');
    }
    if ($this->checkCsrfToken('/diary/edit', $this->request->getPost('csrf_token')) !==true) {
      return $this->redirect('/diary/index');
    }

    $user = $this->session->get('user');
    $request_date = $this->request->getPost('date');
    $picture=NULL;
    $picture_name='uploadfile';
    $picture_path = $this->application->getImagesDir();

    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    //現時刻の取得
    $now = date("Y-m-d H:i:s");
    $date_contents = $diary = $errors = array();
    if ($parameters['date']!==$request_date || $this->check->checkBeDate($request_date) ===false) {
      $errors[]='要求された日付について不正な操作があったため、今日の日付で日記を表示しています';
      $request_date = $today;
    }
    if (count($errors) !== 0) {
      $parameters = array_merge($parameters,$values,$this->getElement($user['id'], $request_date),array('errors'=>$errors,'date'=>$date, 'user'=>$user));
      return $this->render($parameters,'/diary/index');
    }
    $posted_diary = $this->getDiaryPost();
    //チェックボックスのチェック有無で分岐
    if($posted_diary['delete_check']==='1'){
      //diaryテーブルおよび付随するcontentsのデリートフラグを1にする
      $diary_repository = $this->db_manager->getRepository('diary');
      $diary = $diary_repository->getDiary($user['id'],$request_date);
      $diary_repository->deleteOnePage($diary['id'],$now);
      $diary_repository->deleteContents($diary['id'],$now);
    }else{
      //チェックボックスにチェックがされなかった時の処理
      for ($i=0;$i<count($posted_diary['contents']);$i++) {
        // 配列に格納する処理
        $date_contents[$i]=array('emotion_id'=>$posted_diary['emotions'][$i],'situation_id'=>$posted_diary['situations'][$i],'content'=>$posted_diary['contents'][$i]);
      }
      $diary_repository = $this->db_manager->getRepository('diary');
      $count_that_diary = $diary_repository->countThatDiary($user['id'],$request_date);
      $errors=$this->check->checkContents($posted_diary);
      //画像チェック
      if($this->files->checkEmpty($picture_name)!==true){
        if ($this->files->checkAll($picture_name)!==true) {
          $errors[]=$this->files->checkAll($picture_name);
          $picture='';
        }
      }
      //$errorsに値が存在する時の処理
      if (count($errors) !== 0) {
        if($this->files->checkEmpty($picture_name)!==true){
          $picture = $_FILES[$picture_name]['name'];
        }
        return $this->errorsInRegisterAction($date_contents,$errors,$request_date,$user,$posted_diary['feeling'],$picture,$parameters,$count_that_diary['get_value']);
      }
      //ファイル(画像)をimagesディレクトリに保存
      if($this->files->checkEmpty($picture_name)!==true){
        $this->files->saveFile($picture_name,$picture_path,$user['id'],$request_date);
        $picture = $this->files->getNewNameFile();
      }
      $count_that_diary = $diary_repository->countThatDiary($user['id'],$request_date);
      //当日の日記のidがなければinsert、あればupdate
      if ($count_that_diary['get_value']==='1') {
        $diary = $diary_repository->getDiary($user['id'],$request_date);
        //すでに画像が存在した場合、その画像を削除する?
        if(file_exists($picture_path.$diary['picture'])&&strlen($picture)===0){
          //画像を削除する処理
          // unlink($picture_path.$diary['picture']);
        }
        $diary_repository->updateDiary($posted_diary['feeling'],$picture,$now,$diary['id']);
        $diary_contents = $diary_repository->getContents($diary['id']);
        $count_diary_contents=count($diary_contents);
        $count_insert_contents = count($date_contents) - $count_diary_contents;
        $count_delete_contents = $count_diary_contents-count($date_contents);
        $repeat_count=0;
        if ($count_diary_contents > count($date_contents)) {
          $repeat_count = $count_diary_contents;
        } else if ($count_diary_contents < count($date_contents) || $count_diary_contents === count($date_contents)) {
          $repeat_count =count($date_contents);
        }
        $this->registerContentsExistingDiaryAction($repeat_count,$diary_contents,$date_contents,$diary_repository,$diary,$now);
      } else if ($count_that_diary['get_value']==='0') {
        $diary_repository->insertDiary($user['id'],$request_date,$posted_diary['feeling'],$picture,$now);
        $diary = $diary_repository->getDiary($user['id'],$request_date);
        for($i=0;$i<count($date_contents);$i++) {
          $diary_repository->insertContents($diary['id'],$date_contents[$i]['situation_id'],$date_contents[$i]['emotion_id'],$date_contents[$i]['content'],$now);
        }
      }
    }
    //あたらめて登録したデータを取得してrender
    $select_year = array(
      'first'=>date("Y",strtotime($user['create_at'])),
      'last'=>date("Y",strtotime($today)),
      'period'=> date("Y",strtotime($today))-date("Y",strtotime($user['create_at']))
    );
    $parameters = array_merge($parameters,$this->getElement($user['id'], $request_date),array('date'=>$request_date,'user'=>$user,'select_year'=>$select_year));
    return $this->render($parameters,'/diary/index');
  }

  public function getElement($user_id, $request_date)
  {
    $diary_repository = $this->db_manager->getRepository('diary');
    $diary = $diary_repository->getDiary($user_id,$request_date);
    $the_diary_count = $diary_repository->countThatDiary($user_id,$request_date);
    $date_contents = array();
    $kinds = $this->getKinds();
    if($diary!==false){
      $date_contents = $diary_repository->getContents($diary['id']);
      if(empty($diary['picture'])){
        $diary['picture']='';
      }
    }
    return $values = array('date'=>$request_date,'diary'=>$diary,'diary_count'=>$the_diary_count['get_value'],'date_contents'=>$date_contents,'kinds'=>$kinds);
  }

  public function getDiaryPost()
  {
    $feeling = $this->request->getPost('feeling');
    $delete_check = $this->request->getPost('delete');

    $situations=$emotions=$contents=array();
    for ($i=0;$i<5;$i++) {
      $situations[$i] = $this->request->getPost('situation_'.$i);
      $emotions[$i] = $this->request->getPost('emotion_'.$i);
      $contents[$i] = $this->request->getPost('content_'.$i);
    }
    $checked_contents=$this->checkContentsBlank($contents,$emotions,$situations);
    return array('feeling'=>$feeling,'delete_check'=>$delete_check,'situations'=>$checked_contents['situations'],'emotions'=>$checked_contents['emotions'],'contents'=>$checked_contents['contents']);
  }

  public function checkContentsBlank($contents=array(),$emotions=array(),$situations=array())
  {
    for($i=0;$i<5;$i++) {
      if (strlen($contents[$i])===0 ||$this->check->checkBlank($contents[$i])) {
        unset($contents[$i],$emotions[$i],$situations[$i]);
      }
    }

    $contents = array_values($contents);
    $emotions = array_values($emotions);
    $situations = array_values($situations);

    return array('situations'=>$situations,'emotions'=>$emotions,'contents'=>$contents);
  }

  private function errorsInRegisterAction($date_contents,$errors,$request_date,$user,$feeling,$picture,$parameters,$diary_count)
  {
    //各変数で$posted_diaryに変更が必要な場合は変更すること
    $kinds=$this->getKinds();
    $diary=array('feeling_id'=>$feeling,'picture'=>$picture);
    //トークン生成
    $token = $this->generateCsrfToken('/diary/edit');
    $values = array('kinds'=>$kinds,'errors'=>$errors,'date'=>$request_date, 'user'=>$user,'date_contents'=>$date_contents,'diary'=>$diary,'token'=>$token,'diary_count'=>$diary_count);
    $parameters = array_merge($parameters,$values);
    return $this->render($parameters,'/diary/edit');
  }

  private function registerContentsExistingDiaryAction($repeat_count,$diary_contents,$date_contents,$diary_repository,$diary,$now)
  {
    for($i=0;$i<$repeat_count;$i++){
      //入力[$i]・DB[$i]共に存在するとき
      if (isset($diary_contents[$i]) && isset($date_contents[$i])){
        if (($date_contents[$i]['emotion_id']!==$diary_contents[$i]['emotion_id'] || $date_contents[$i]['situation_id']!==$diary_contents[$i]['situation_id'] || $date_contents[$i]['content']!==$diary_contents[$i]['content']) && !empty($date_contents[$i]['content'])){
          //いずれかに変更があり内容が空白ではない時アップデート
          $diary_repository->updateContents($date_contents[$i]['situation_id'],$date_contents[$i]['emotion_id'],$date_contents[$i]['content'],$now,$diary['id'],$diary_contents[$i]['id']);
        }
      }
      //入力[$i]ありDB[$i]なしのときインサート
      if (isset($date_contents[$i]) && !isset($diary_contents[$i])){
        if(!empty($date_contents[$i]['content'])){
          $diary_repository->insertContents($diary['id'],$date_contents[$i]['situation_id'],$date_contents[$i]['emotion_id'],$date_contents[$i]['content'],$now);
        }
      }
      //入力[$i]なしDB[$i]ありのときデリート
      if (isset($diary_contents[$i]) && empty($date_contents[$i]['content'])){
        //デリート
        $diary_repository->deleteOneContent($diary_contents[$i]['id'],$now);
      }
      //入力[$i]なしDB[$i]なしのときは何もしない
      if (!isset($diary_contents[$i]) && !isset($date_contents[$i])){
        return;
      }
    }
  }
}
