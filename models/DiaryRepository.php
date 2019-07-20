<?php
class DiaryRepository extends Repository
{
  public function getKindsDevides() {
    //感情の大まかな分類を取得
    $sql='SELECT * FROM devides ORDER BY id';
    $this->execute($sql);
  }

  public function getKindsEmotions() {
    //感情の種類を取得
      $sql='SELECT * FROM emotions ORDER BY id';
      $this->execute($sql);
  }

  public function getKindsSituations() {
    //状況の種類を取得
  $sql='SELECT * FROM situations ORDER BY id';
    $this->execute($sql);
  }

  public function getDiary($user_id,$date) {
  //一日の評価、作成日時、更新日時、写真のファイルパスを取得
  $sql='SELECT * FROM diaries WHERE user_id=:user_id AND the_date=:the_date';
  $this->execute($sql,array(':user_id'=>$user_id,':date'=>$date));
  }

  public function countThatDiary($user_id,$date) {
  //その日の日記が存在するかカウント
  $sql='SELECT COUNT (id) FROM diaries WHERE user_id=:user_id AND the_date=:the_date';
  $this->execute($sql,array(':user_id'=>$user_id,':the_date'=>$date));
  }


public function getContents($diary_id) {
//出来事のid、感情、シチュエーション、内容、作成日時、更新日時を取得。fetchAll
$sql='SELECT * FROM contents WHERE diary_id=:diary_id';
$this->execute($sql,array(':dairy_id'=>$diary_id));
}

public function insertDiary($user_id,$date,$feeling,$picture_id,$create_at,$diary_id) {
//日記の日付、1日の評価、作成日時、写真のファイルパスをインサート。
$sql='INSERT INTO diaries(user_id,the_date,feeling_id,picture,create_at) VALUES (:user_id,:the_date,:feeling_id,:picture,:create_at)';
$this->execute($sql,array(':user_id'=>$user_id,':date'=>$date,':feeling_id'=>$feeling_id,':picture'=>$picture,':create_at'=>$create_at));
}

public function updateDiary($feeling,$picture,$update_at,$diary_id) {
//日記の日付、1日の評価、作成日時、写真のファイルパスを更新。
$sql='UPDATE diaries SET feeling_id=:feeling,picture=:picture,update_at=:update_at WHERE diary_id=:diary_id';
$this->execute($sql,array(':feeling'=>$feeling,':diary_id'=>$diary_id,':picture'=>$picture,':update_at'=>$update_at));
}

public function deleteDiary($user_id,$delete_at) {
//日記の削除。deleteに1を。
$sql='UPDATE diaries SET is_delete = 1,delete_at = :delete_at WHERE user_id = :user_id';
$this->execute($sql,array(':user_id'=>$user_id,':delete_at'=>$delete_at));
}

public function insertContents($user_id,$diary_id,$situation_id,$emotion_id,$content,$create_at,$line_up) {
//ユーザID、日記のID,シチュエーション、内容、作成日時、更新日時を取得。
$sql='INSERT INTO diaries(user_id,diary_id,situation_id,emotion_id,content,create_at,line_up) VALUES (:user_id,:diary_id,:situation_id,:emotion_id,:content,:create_at,:line_up)';
$this->execute($sql,array(':user_id'=>$user_id,':diary_id'=>$diary_id,':situation_id'=>$situation_id,':emotion_id'=>$emotion_id,':content'=>$content,':create_at'=>$create_at,':line_up'=>$line_up));
}

public function updateContents($situation_id,$emotion_id,$content,$update_at,$diary_id,$line_up) {
//ユーザID、日記のID,シチュエーション、内容、更新日時をアップデート。
$sql='UPDATE contents SET situation_id=:situation_id,emotion_id=:emotion_id,content=:content,update_at=:update_at WHERE diary_id=:diary_id AND line_up=:line_up';
$this->execute($sql,array(':situation_id'=>$situation_id,':emotion_id'=>$emotion_id,':content'=>$content,':update_at'=>$update_at,':diary_id'=>$diary_id,':line_up'=>$line_up));
}

public function deleteContents($user_id,$delete_at) {
//日記の削除。deleteに1を。
$sql='UPDATE contents SET is_delete=1,delete_at=:delete_at WHERE user_id=:user_id';
$this->execute($sql,array(':delete_at'=>$delete_at,':user_id'=>$user_id));
}

public function countDays($user_id, $is_delete = 0) {
  //日記を書いてある日数を取得
  $sql='SELECT COUNT(user_id)  FROM diaries WHERE user_id=:user_id AND is_delete=:is_delete';
  $this->execute($sql,array(':user_id'=>$user_id,':is_delete'=>$is_delete));
}

public function countDevides($user_id,$devide_id,$delete = 0) {
//ポジティブ、ネガティブ、その他の感情について数をカウントする
$sql='SELECT COUNT(id) FROM contents WHERE user_id=:user_id AND devide_id=:devide_id ';
$this->execute($sql,array(':user_id'=>$user_id,':devide_id'=>$devide_id));
}

public function countEmotion($user_id,$emotion_id,$period_first,$period_last) {
  //ある１種類の感情について数える ex)楽しい、悲しい
  $sql='SELECT COUNT(c.id) FROM contents AS c WHERE c.user_id=:user_id AND c.emotion_id=:emotion_id AND d.the_date>=:period_first AND d.the_date<:period_last JOIN diaries AS d ON c.diary_id=d.id';
  $this->execute($sql,array(':user_id'=>$user_id,':emotion_id'=>$emotion_id,':period_first'=>$period_first,':period_last'=>$period_last));
}

public function countContents($user_id,$delete = 0) {
  //内容を数える
  $sql='SELECT COUNT(id) FROM contents WHERE user_id=:user_id AND delete_at=:delete_at';
  $this->execute($sql,array(':user_id'=>$user_id,':delete_at'=>$delete_at));
}


}
