<?
class Router
{
  //ルートを格納するプロパティ
  protected $routes;
  //todo コンストラクタ 下記を実行
  public function __construct($route_definitions) {
    $this->routes = $this->normalizeRoutes($route_definitions)
  }

  // ルーティング定義配列中の動的パラメータ指定を正規表現で扱える形式に変換して返す
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

  //パスと正規化したルーティングのマッチした値を返す
  public function pathMatch($path) {
    //$pathの1文字目が / か確認。ない場合は付与。
  if (substr($path, 0, 1) !=='/') {
    $path = '/'.$path;
  }
    //$this->routesとマッチングを行う。
    //マッチしたら:hoge等の箇所を名前付きで値を取得し元の配列に加えて返す。falseの時はfalseを返す。
    foreach($routes as $route => $parameters){
    if(preg_match('#^'.$route.'$#', $path, $matches) {
      return array_merge($parameters,$matches);
    }
  }
    return false;
  }
}
