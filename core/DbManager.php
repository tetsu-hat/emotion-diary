<?php
class DbManager
{
  //DB接続に関わるPDOインスタンス等を格納するプロパティ
  protected $connection = '';
  protected $repositories = array();
  //todo 接続情報の初期化とPDOのインスタンス化とそれの格納
  public function connect($parameters)
  {
    $db_parameters = array_merge(array(
      'dsn'      =>'',
      'user'     =>'',
      'password' =>'',
      'options'  => array(),
    ),$parameters);

    $pdo = new PDO(
      $db_parameters['dsn'],
      $db_parameters['user'],
      $db_parameters['password'],
      $db_parameters['options']
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->connection = $pdo;
  }
  //現在connectionプロパティに格納されているインスタンスを返す
  public function getConnection()
  {
    if(!empty($this->connection)) {
      return $this->connection;
    }
    return false;
  }
  //modelsディレクトリにあるhogeRepository(引数:PDOインスタンス)インスタンスを格納、そして返す
  public function getRepository($repository_name)
  {
    $repository_class = ucfirst($repository_name.'Repository');
    //hogeRepositoryクラスが存在するか
    if (isset($repository_class)) {
      //hogeRepositoryインスタンスがすでに存在するか
      if (!isset($this->repositories[$repository_name])) {
        $repository = new $repository_class($this->connection);
        $this->repositories[$repository_name] = $repository;
        return $repository;
      }
      return $this->repositories[$repository_name];
    }
  }
  //DB接続の解放
  public function __destruct()
  {
    unset($this->repositories);
    unset($this->connection);
  }
}
