<?php
abstract class DbRepository
{
  //PDOインスタンスを格納するプロパティ
  protected $connection;
  //コンストラクトタ 以下のメソッド実行
  public function __construct($pdo) {
    $this->setConnection($pdo);
  }
  //PDOインスタンスの格納
  public function setConnection($pdo) {
    $this->connection = $pdo;
  }
  //executeに係る処理とstmtを返す
  public function execute($sql,$parameters) {
    $stmt = $this->connection->prepare($sql);
    $stmt->execute($parameters);
    return $stmt;
  }
  //fetchに係る処理結果を返す
  public function fetch($sql,$parameters) {
    $stmt = $this->execute($sql,$parameters)->fetch(PDO::FETCH_ASSOC);
    return $stmt;
  }

  //fetchAllに係る処理結果を返す
  public function fetchAll($sql,$parameters) {
    $stmt = $this->execute($sql,$parameters)->fetchAll(PDO::FETCH_ASSOC);
    return $stmt;
  }

}
