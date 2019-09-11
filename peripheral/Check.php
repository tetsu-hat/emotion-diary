<?php
class Check
{
  protected $application;
  protected $db_manager;
  protected $session;

  private $blank='/^[¥s　]+$/';
  private $mail_format='/^(([a-zA-Z0-9]{1})|(^[a-zA-Z0-9]{1}[a-zA-Z0-9]{1})|(^[a-zA-Z0-9]{1}[a-zA-Z0-9\-\._]+[a-zA-Z0-9]{1}))@[a-z\-\._]+\.(([a-z0-9])|([a-z0-9\-]+\.[a-z]))+$/';
  private $date_format='/^[1-2]{1}[0-9]{3}-0[1-9]{1}|1[0-2]{1}-[0]{1}[1-9]{1}|[1-2]{1}[0-9]|[3]{1}[0-1]{1}$/';
  private $unavailable_character_mail='/[^a-zA-Z0-9\-\._@]/';
  private $unavailable_character='/[^a-zA-Z0-9\-_@]/';
  private $pass_string='/^[a-zA-Z0-9\-_@]+$/';
  private $name_string='/^[a-zA-Z0-9\-_@]+$/';
  private $sex_string='/^[0-2]$/';

  public function __construct($application)
  {
    $this->application = $application;
    $this->db_manager = $this->application->getDbManager();
    $this->session = $this->application->getSession();
  }

  public function checkBlank($string){
    if(preg_match($this->blank, $string)===1 || strlen($string)===0){
      return true;
    }
    return false;
  }

  public function checkAvailableCharacter($string,$character){
    if(preg_match($character,$string)===1){
      return true;
    }
    return false;
  }

  public function checkFormat($format,$string){
    if(preg_match($format, $string)===1){
      return true;
    }
    return false;
  }

  public function checkChoice($choice){
    if($choice!=='0'){
      return true;
    }
    return false;
  }

  // 文字列から任意の文字の数を数える
  public function countCharacter($haystack,$needle){
    if(mb_substr_count($haystack,$needle)>0){
      return true;
    }
    return false;
  }

  //文字列の文字数を数える
  public function scopeString($string,$min_length,$max_length){
    if(mb_strlen($string)>$min_length && mb_strlen($string)<$max_length){
      return true;
    }
    return false;
  }

  public function checkName($name){
    //空白チェック
    if($this->checkBlank($name)){
      return '名前を入力してください。';
    }

    if(!$this->scopeString($name,0,51)){
      return '名前に使用できる文字数は1文字以上50文字以内です。';
    }
  }

  public function checkMail($mail,$action){
    $user_repository=$this->db_manager->getRepository('user');
    $user=$this->session->get('user');
    $count_mail=$user_repository->countAccountByMail($mail);
    // 空白チェック
    if($this->checkBlank($mail)){
      return 'メールアドレスを入力してください。';
    }
    // 使用している文字
    if($this->checkAvailableCharacter($mail,$this->unavailable_character_mail)){
      return 'メールアドレスに使用できない文字が含まれています。半角英(大小)数字および記号-_.@のみ使用できます。';
    }
    // 文字数
    if(!$this->scopeString($mail,0,51)){
      return 'メールアドレスに使用できる文字数は1文字以上50文字以内です。';
    }
    //形式
    if(!$this->checkFormat($this->mail_format,$mail)){
      return 'メールアドレスに使用できないメール形式です。';
    }

    if(isset($user['mail']) && isset($mail)){
      //登録修正の場合
      if($user['mail']!==$mail && $count_mail['get_value']!=='0'){
        return 'このメールアドレスは使用できません。';
      }
    }else if (!isset($user['mail']) && isset($mail)){
      //サインイン
      if($action === 'authenticate' && $count_mail['get_value']!=='0'){
        return 'このメールアドレスは使用できません。';
      }

      //新規登録
      if($action==='register' && $count_mail['get_value']!=='0'){
        return 'このメールアドレスは使用できません。';
      }

    }
  }

  public function checkSex($sex){
    if(!$this->checkFormat($this->sex_string,$sex)){
      return '性別の選択にいたずらしましたか？';
    }
  }

  public function checkPassword($password){
    // 空白チェック
    if($this->checkBlank($password)){
      return 'パスワードを入力してください。';
    }
    // 使用している文字
    if($this->checkAvailableCharacter($password,$this->unavailable_character)){
      return 'パスワードに使用できない文字が含まれています。半角英(大小)数字および記号-_@のみ使用できます。';
    }
    // 文字数
    if(!$this->scopeString($password,7,17)){
      return 'パスワードに使用できる文字数は8文字以上16文字以内です。';
    }
  }

  public function checkNewPassword($new_password){
    // 空白チェック
    if($this->checkBlank($new_password)){
      return '新しいパスワードを入力してください。';
    }
    // 使用している文字
    if($this->checkAvailableCharacter($new_password,$this->unavailable_character)){
      return '新しいパスワードに使用できない文字が含まれています。半角英(大小)数字および記号-_@のみ使用できます。';
    }
    // 文字数
    if(!$this->scopeString($new_password,7,17)){
      return '新しいパスワードに使用できる文字数は8文字以上16文字以内です。';
    }
  }

  public function checkConfirmPassword($password,$confirm_password){
    // 空白チェック
    if($this->checkBlank($confirm_password)){
      return '確認用パスワードを入力してください。';
    }

    if($password!==$confirm_password) {
      return '確認用パスワードが一致しません。';
    }
  }
  //存在する日なのかチェックするメソッド
  public function checkBeDate($request_date) {
    //要求された日付が空ではないかチェック
    if(empty($request_date)) {
      return false;
    }
    //要求された日付が空ではなく形式がY-m-dであるかチェック
    if (!empty($request_date) && !$this->checkFormat($this->date_format,$request_date)) {
      return false;
    }
    //以下、各月の日数のチェック
    //30日までの月、31日までの月、2月それぞれ
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    $year = date('Y', strtotime($request_date));
    $month = date('m', strtotime($request_date));
    $day = date('d', strtotime($request_date));
    //30日まで4,6,9,11
    if(checkdate($month,$day ,$year)){
      return true;
    }
    return false;
  }

  public function checkSigninInput($mail,$password,$action){
    $errors = rray();
    if($this->checkMail($mail,$action) !== null){
      $errors[] = $this->checkMail($mail,$action);
    }
    if($this->checkPassword($password) !== null){
      $errors[] = $this->checkPassword($password);
    }
    return $errors;
  }

  public function checkSignupInput($name,$mail,$password,$confirm_password,$action){
    $errors = array();
    if($this->checkName($name) !== null){
      $errors[] = $this->checkName($name);
    }
    if($this->checkMail($mail,$action) !== null){
      $errors[] = $this->checkMail($mail,$action);
    }
    if($this->checkPassword($password) !== null){
      $errors[] = $this->checkPassword($password);
    }
    if($this->checkConfirmPassword($password,$confirm_password)){
      $errors[] = $this->checkConfirmPassword($password,$confirm_password);
    }
    return $errors;
  }

  public function checkPersonalInput($name,$mail,$sex,$password,$new_password,$confirm_password,$action){
    $errors = array();
    if($this->checkName($name) !== null){
      $errors[] = $this->checkName($name);
    }
    if($this->checkMail($mail,$action) !== null){
      $errors[] = $this->checkMail($mail,$action);
    }
    if($this->checkSex($sex) !== null){
      $errors[] = $this->checkSex($sex);
    }
    if($this->checkPassword($password) !== null){
      $errors[] = $this->checkPassword($password);
    }
    if(!$this->checkBlank($new_password)||!$this->checkBlank($confirm_password)){
      if($this->checkNewPassword($new_password) !== null){
        $errors[] = $this->checkNewPassword($new_password);
      }
      if($this->checkConfirmPassword($new_password,$confirm_password) !== null){
        $errors[] = $this->checkConfirmPassword($new_password,$confirm_password);
      }
    }
    return $errors;
  }

  public function checkDeleteAccount($password,$user_password){
    $errors = array();
    if($this->checkPassword($password) !== null){
      $errors[] = $this->checkPassword($password);
    }else if (!password_verify($password,$user_password)) {
      $errors[] = 'パスワードに誤りがあります';
    }
    return $errors;
  }

  public function checkContents($posted_diary=array()){
    $errors=array();
    if($posted_diary['feeling'] === '0') {
      $errors[] = '今日一日の調子が選択されていません。';
    }
    //文字数が140字以下かチェック
    for ($i=0;$i<count($posted_diary['contents']);$i++) {
      if (mb_strlen($posted_diary['contents'][$i]) > 140)  {
        $errors[] = ($i+1).'番目の日記の内容は140字以内で入力してください。';
      }
      if($posted_diary['emotions'][$i]==='0'){
        $errors[] = ($i+1).'番目の日記のに対する感情を選択してください。「未選択」は不可です。';
      }
      if($posted_diary['situations'][$i]==='0'){
        $errors[] = ($i+1).'番目の日記のに対する状況を選択してください。「未選択」は不可です。';
      }
    }
    return $errors;
  }
}

?>
