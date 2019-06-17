<?
class Router
{
  //ルートを格納するプロパティ
  protected $routes;
  //todo コンストラクタ 下記を実行
  public function __construct() {

  }

  //todo ルーティング定義配列中の動的パラメータ指定を正規表現で扱える形式に変換して返す
  // ルーティング中の『/:』の箇所の正規化
  public function normalizeRoutes($route_definitions) {
    $routes = array();
    foreach($route_definitions as $path => $parameters){
      $tokens = explode('/',ltrim($path,'/'));
      foreach($tokens as $i => $token) {
        if (0 === strpos($token, ':')) {
          $name = substr($token, 1);
          $token = '(?P<'. $name .'>[^/]+)';
        }
        $tokens[$i] = $token;
      }
      $pattern = '/'. implode('/', $tokens);
      $routes[$pattern] = $parameters;
    }
    return $routes;
  }

  //todo パスと正規化したルーティングのマッチした値を返す
  public function pathMatch($path) {

  }
}
