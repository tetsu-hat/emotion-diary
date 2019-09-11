<?php
class UserRepository extends DbRepository
{
  //ユーザ情報の取得
  public function getUser($mail,$is_delete=0)
  {
    $sql = 'SELECT u.id AS id,u.name AS name,u.mail AS mail,u.password AS password,u.create_at AS create_at,u.update_at AS update_at,u.is_delete AS is_delete,u.delete_at AS delete_at,s.id AS sex_id,s.name AS sex_name
    FROM users AS u JOIN sex AS s ON u.sex_id=s.id WHERE u.mail=:mail AND u.is_delete=:is_delete';
    return $this->fetch($sql,array(':mail'=>$mail,':is_delete'=>$is_delete));
  }
  //ログインや新規登録、登録内容変更時にメールがすでに登録されているかカウントして確認
  public function countAccountByMail($mail,$is_delete=0)
  {
    $sql = 'SELECT COUNT(id) AS get_value FROM users WHERE mail=:mail AND is_delete=:is_delete';
    $count=$this->fetch($sql,array(':mail'=>$mail,':is_delete'=>$is_delete));
    return $count;
  }
  // DBにインサート
  public function insertUser($name,$mail,$password,$create_at)
  {
    $sql = 'INSERT INTO users(name,mail,password,create_at) VALUES (:name,:mail,:password,:create_at)';
    return $this->execute($sql,array(':name'=>$name,':mail'=>$mail,':password'=>$password,':create_at'=>$create_at));
  }
  //ユーザ情報を更新
  public function updateUser($new_name,$new_mail,$sex,$new_password,$update_at,$mail,$password,$is_delete=0)
  {
    $sql = 'UPDATE users SET name=:new_name,mail=:new_mail,sex_id=:sex_id,password=:new_password,update_at=:update_at WHERE mail=:mail AND password=:password AND is_delete=:is_delete';
    return $this->execute($sql,array(':new_name'=>$new_name,':new_mail'=>$new_mail,':sex_id'=>$sex,':new_password'=>$new_password,':update_at'=>$update_at,':mail'=>$mail,':password'=>$password,':is_delete'=>$is_delete));
  }
  //is_deleteに1を。
  public function deleteAccount($mail,$password,$delete_at)
  {
    $sql = 'UPDATE users SET is_delete=1,delete_at=:delete_at WHERE mail=:mail AND password=:password';
    return $this->execute($sql,array(':mail'=>$mail,':password'=>$password,':delete_at'=>$delete_at));
  }
}
