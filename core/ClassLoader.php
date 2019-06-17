<?php
class ClassLoader
{
//todo オートロードの機能
protected $directories;

public function register() {
    spl_autoload_register(array($this,'loadClass'));
}

public function registerDirectory($directory) {
  $this->directories[] = $directory;
}

public function loadClass($class) {
  foreach($this->directories as $directory) {
    $file = $directory.'/'.$class.'.php';
    if (is_readable($file)) {
      require $this;
    }
  }
}
}
