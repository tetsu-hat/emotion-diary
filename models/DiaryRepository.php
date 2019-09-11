<?php
class DiaryRepository extends DbRepository
{
    //総評の感情のを取得
  public function getKindsFeelings()
  {
    $sql = 'SELECT * FROM feelings ORDER BY id';
    return $this->fetchAll($sql,array());
  }
    //感情の大まかな分類を取得
  public function getKindsDevides()
  {
    $sql = 'SELECT * FROM devides ORDER BY id';
    return $this->fetchAll($sql,array());
  }
  //感情の種類を取得
  public function getKindsEmotions()
  {
    $sql = 'SELECT * FROM emotions ORDER BY id';
    return $this->fetchAll($sql,array());
  }
  //状況の種類を取得
  public function getKindsSituations()
  {
    $sql = 'SELECT * FROM situations ORDER BY id';
    return $this->fetchAll($sql,array());
  }
  //一日の評価、作成日時、更新日時、写真のファイルパスを取得
  public function getDiary($user_id,$date,$is_delete=0)
  {
    $sql = 'SELECT d.id,d.the_date,f.id AS feeling_id,f.name AS feeling,d.picture,d.create_at,d.update_at FROM diaries AS d JOIN feelings AS f ON d.feeling_id = f.id WHERE d.user_id=:user_id AND d.the_date=:the_date AND d.is_delete=:is_delete';
    return $this->fetch($sql,array(':user_id'=>$user_id,':the_date'=>$date,'is_delete'=>$is_delete));
  }
//その日の日記が存在するかカウント
  public function countThatDiary($user_id,$date,$is_delete=0)
  {
    $sql = 'SELECT COUNT(id) AS get_value FROM diaries WHERE user_id=:user_id AND the_date=:the_date AND is_delete=:is_delete';
    return $this->fetch($sql,array(':user_id'=>$user_id,':the_date'=>$date,'is_delete'=>$is_delete));
  }
//画像の名前を全て取得する
  public function getPictures($user_id,$is_delete=0)
  {
      $sql = 'SELECT picture FROM diaries WHERE user_id=:user_id AND is_delete=:is_delete AND picture IS NOT NULL ORDER BY id';
        return $this->fetchAll($sql,array(':user_id'=>$user_id,':is_delete'=>$is_delete));
  }
//出来事のid、感情、シチュエーション、内容、作成日時、更新日時を取得。fetchAll
  public function getContents($diary_id,$is_delete=0)
  {
    $sql = 'SELECT c.id,c.diary_id,s.id AS situation_id,s.name AS situation,
    e.id AS emotion_id,e.name AS emotion,c.content,c.create_at,c.update_at
    FROM contents AS c JOIN emotions AS e ON c.emotion_id = e.id JOIN situations AS s ON c.situation_id = s.id
    WHERE c.diary_id=:diary_id AND c.is_delete=:is_delete';
    return $this->fetchAll($sql,array(':diary_id'=>$diary_id,'is_delete'=>$is_delete));
  }
  //日記の日付、1日の評価、作成日時、写真のファイルパスをインサート。
  public function insertDiary($user_id,$date,$feeling_id,$picture,$create_at)
  {
    $sql = 'INSERT INTO diaries(user_id,the_date,feeling_id,picture,create_at) VALUES (:user_id,:the_date,:feeling_id,:picture,:create_at)';
    return $this->execute($sql,array(':user_id'=>$user_id,':the_date'=>$date,':feeling_id'=>$feeling_id,':picture'=>$picture,':create_at'=>$create_at));
  }
  //日記の日付、1日の評価、作成日時、写真のファイルパスを更新。
  public function updateDiary($feeling_id,$picture,$update_at,$diary_id,$is_delete=0)
  {
    $sql = 'UPDATE diaries SET feeling_id=:feeling_id,picture=:picture,update_at=:update_at WHERE id=:diary_id AND is_delete=:is_delete';
    return $this->execute($sql,array(':feeling_id'=>$feeling_id,':picture'=>$picture,':update_at'=>$update_at,':diary_id'=>$diary_id,'is_delete'=>$is_delete));
  }
  //日記の削除。deleteに1を。
  public function deleteDiary($user_id,$delete_at)
  {
    $sql = 'UPDATE diaries SET is_delete = 1,delete_at = :delete_at WHERE user_id = :user_id';
    return $this->execute($sql,array(':user_id'=>$user_id,':delete_at'=>$delete_at));
  }
  //日記の削除。deleteに1を。
  public function deleteOnePage($diary_id,$delete_at,$is_delete=0)
  {
    $sql = 'UPDATE diaries SET is_delete = 1,delete_at = :delete_at WHERE id = :diary_id AND is_delete=:is_delete';
    return $this->execute($sql,array(':diary_id'=>$diary_id,':delete_at'=>$delete_at,':is_delete'=>$is_delete));
  }
  //ユーザID、日記のID,シチュエーション、内容、作成日時、更新日時を挿入。
  public function insertContents($diary_id,$situation_id,$emotion_id,$content,$create_at)
  {
    $sql = 'INSERT INTO contents(diary_id,situation_id,emotion_id,content,create_at) VALUES (:diary_id,:situation_id,:emotion_id,:content,:create_at)';
    return $this->execute($sql,array(':diary_id'=>$diary_id,':situation_id'=>$situation_id,':emotion_id'=>$emotion_id,':content'=>$content,':create_at'=>$create_at));
  }
  //ユーザID、日記のID,シチュエーション、内容、更新日時をアップデート。
  public function updateContents($situation_id,$emotion_id,$content,$update_at,$diary_id,$content_id,$is_delete=0)
  {
    $sql = 'UPDATE contents SET situation_id=:situation_id,emotion_id=:emotion_id,content=:content,update_at=:update_at WHERE diary_id=:diary_id AND id=:id AND is_delete=:is_delete';
    return $this->execute($sql,array(':situation_id'=>$situation_id,':emotion_id'=>$emotion_id,':content'=>$content,':update_at'=>$update_at,':diary_id'=>$diary_id,':id'=>$content_id,'is_delete'=>$is_delete));
  }
  //日記の削除。deleteに1を。
  public function deleteContents($diary_id,$delete_at,$is_delete=1)
  {
    $sql = 'UPDATE contents SET is_delete=:is_delete,delete_at=:delete_at WHERE diary_id=:diary_id';
    return $this->execute($sql,array('is_delete'=>$is_delete,':delete_at'=>$delete_at,':diary_id'=>$diary_id));
  }
  //日記の削除。deleteに1を。
  public function deleteOneContent($content_id,$delete_at,$is_delete=1)
  {
    $sql = 'UPDATE contents SET is_delete=:is_delete,delete_at=:delete_at WHERE id=:id';
    return $this->execute($sql,array(':is_delete'=>$is_delete,':delete_at'=>$delete_at,':id'=>$content_id));
  }
//日記を書いてある日数を取得
  public function countDays($user_id, $is_delete = 0)
  {
    $sql = 'SELECT COUNT(user_id) AS get_value FROM diaries WHERE user_id=:user_id AND is_delete=:is_delete';
    return $this->fetch($sql,array(':user_id'=>$user_id,':is_delete'=>$is_delete));
  }
  //ポジティブ、ネガティブ、その他の感情について数をカウントする
  public function countDevides($user_id,$devide_id,$is_delete = 0)
  {
    $sql = 'SELECT COUNT(c.id) AS get_value FROM contents AS c JOIN diaries AS d ON c.diary_id=d.id JOIN emotions AS e ON c.emotion_id=e.id JOIN devides AS de ON e.devide_id=de.id WHERE d.user_id=:user_id AND e.devide_id=:devide_id AND c.is_delete=:is_delete AND d.is_delete=:is_delete';
    return $this->fetch($sql,array(':user_id'=>$user_id,':devide_id'=>$devide_id,':is_delete'=>$is_delete));
  }
  //期間ごとにポジティブ、ネガティブ、その他の感情について数をカウントする
  public function countDevidesByPeriod($user_id,$devide_id,$period_first,$period_last,$is_delete = 0)
  {
    $sql = 'SELECT COUNT(c.id) AS get_value FROM contents AS c JOIN diaries AS d ON c.diary_id=d.id JOIN emotions AS e ON c.emotion_id=e.id
    WHERE d.user_id=:user_id AND e.devide_id=:devide_id AND d.the_date>=:period_first AND d.the_date<:period_last AND c.is_delete=:is_delete AND d.is_delete=:is_delete AND e.devide_id=:devide_id';
    return $this->fetch($sql,array(':user_id'=>$user_id,':devide_id'=>$devide_id,':is_delete'=>$is_delete,':period_first'=>$period_first,':period_last'=>$period_last));
  }
  //ある１種類の感情について数える ex)楽しい、悲しい
  public function countEmotionByPeriod($user_id,$emotion_id,$period_first,$period_last,$is_delete = 0)
  {
    $sql = 'SELECT COUNT(c.id) AS get_value FROM contents AS c
    JOIN diaries AS d ON c.diary_id=d.id JOIN emotions AS e ON c.emotion_id=e.id
    WHERE d.user_id=:user_id AND c.emotion_id=:emotion_id
    AND d.the_date>=:period_first AND d.the_date<=:period_last AND c.is_delete=:is_delete AND d.is_delete=:is_delete';
    return $this->fetch($sql,array(':user_id'=>$user_id,':emotion_id'=>$emotion_id,':period_first'=>$period_first,':period_last'=>$period_last,':is_delete'=>$is_delete));
  }
    //内容を数える。必ずしもdiaryテーブルのidとcontentsテーブルのdiary_idが存在して一致するとは限らないため外部結合
  public function countContents($user_id,$is_delete = 0)
  {
    $sql = 'SELECT COUNT(c.id) AS get_value FROM contents AS c JOIN diaries AS d ON c.diary_id = d.id WHERE d.user_id=:user_id AND c.is_delete=:is_delete AND d.is_delete=:is_delete';
    return $this->fetch($sql,array(':user_id'=>$user_id,':is_delete'=>$is_delete));
  }

}
