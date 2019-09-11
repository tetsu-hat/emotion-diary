<?php
class Files{
  private $picture = '';
  private $format = '/^image\/(jpg|jpeg|png|bmp)$/';
  // $_FILESであることが前提
  public function getFile($name)
  {
    if(!empty($_FILES[$name]['tmp_name']) && is_uploaded_file($_FILES[$name]['tmp_name'])){
      return $_FILES[$name];
    }
    return false;
  }

  public function checkIsArray($name)
  {
    if(is_array($_FILES[$name]['tmp_name'])){
      return true;
    }
    return false;
  }

  public function checkEmpty($name)
  {
    if($_FILES[$name]['error']===4){
      return true;
    }
    return false;
  }

  public function checkErrorCode($name)
  {
    if($_FILES[$name]['error']===0 || $_FILES[$name]['error']===4){
      //0はエラーなし、4はファイルがアップロードされていない
      return true;
    }
    return false;
  }

  public function checkImangeType($name)
  {
    if(preg_match($this->format,$_FILES[$name]['type'])){
      return true;
    }
    return false;
  }

  public function checkSize($name)
  {
    //3MB以下ならtrue
    if($_FILES[$name]['size']<=1048576*5){
      return true;
    }
    return false;
  }

  public function getCountFiles($name)
  {
    $number = count($_FILES[$name]['name']);
    return $number;
  }
  //拡張子を取得。
  public function getExtension($name)
  {
    $position = strpos($_FILES[$name]['type'],'/')+1;
    return $extension = substr($_FILES[$name]['type'], $position);
  }

  public function getNewNameFile()
  {
    return $this->picture;
  }

  public function saveFile($name,$directory_path,$user_id,$diary_date)
  {
    $file = $user_id.'_'.date('Ymd',strtotime($diary_date)).'.'.$this->getExtension($name);
    $this->picture = $file;
    return move_uploaded_file($_FILES[$name]['tmp_name'],$directory_path.'/'.$file);
  }

  public function checkAll($name)
  {
    if($this->checkIsArray($name)){
      return '配列のため予期しない入力が行われた可能性があります。';
    }
    if(!$this->checkErrorCode($name)){
      return 'エラーコードに該当しました。';
    }
    if(!$this->checkImangeType($name)){
      return '使用できない形式です。';
    }
    if(!$this->checkSize($name)){
      return 'サイズが大きいです。5MBまでの画像にしてください。';
    }
    return true;
  }
}
