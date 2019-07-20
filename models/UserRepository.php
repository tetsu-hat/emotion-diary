<?php
class UserRepository extends Repository
{
  //ユーザ情報の取得
  public function getUserInfo($mail,$password,$is_delete=0) {
    //deleteが0であること。
$sql='SELECT * FROM users WHERE mail=:mail AND password=:password AND is_delete=:is_delete';
$this->execute($sql,array(':mail'=>$mail,':password'=>$passwpord,':is_delete'=>$is_delete));
  }

//ログインや新規登録、登録内容変更時にメールがすでに登録されているかカウントして確認
  public function countAccountByMail($mail,$is_delete=0){
$sql='SELECT COUNT(id) FROM users WHERE mail=:mail AND is_delete=:is_delete';
$this->execute($sql,array(':mail'=>$$mail,':is_delete'=>$is_delete));
  }

  // DBにインサート
  public function insertUserInfo($name,$mail,$password,$create_at) {
  $sql='INSERT INTO users(name,mail,password,create_at) VALUES (:name,:mail,:password,:create_at)';
    $this->execute($sql,array(':name'=>$name,':mail'=>$mail,':password'=>$password,':create_at'=>$create_at));
  }
  //ユーザ情報を更新
  public function updateUserInfo($new_name,$new_mail,$new_password,$update_at,$mail,$password) {
$sql='UPDATE users SET name=:new_name,mail=:new_mail,password=:new_password,update_at=:update_at WHERE mail=:mail AND password=:password';
$this->execute($sql,array(':name'=>$new_name,':mail'=>$new_mail,':new_password'=>$new_password,':update_at'=>$update_at,':mail'=>$mail,':password'=>$password));
  }

  public function deleteAccount($mail,$password,$delete_at) {
    //delete_atに現在の時刻を。
    //deleteに1を。
  $sql='UPDATE users SET is_delete=1,delete_at=:delete_at WHERE mail=:mail AND password=:password';
  $this->execute($sql,array(':mail'=>$mail,':password'=>$password,':delete_at'=>$delete_at));
  }
 }
