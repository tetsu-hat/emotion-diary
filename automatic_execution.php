<?php
// ClassLoder.phpを読み込む
require 'core/ClassLoader.php';
// ClassLoderインスタンス化
$class_load = new ClassLoader;
// オートロードの対象のディレクトリ決める
$class_load->registerDirectory(dirname(__FILE__.'/core'));
$class_load->registerDirectory(dirname(__FILE__.'/models'));
// オートロード実行
$class_load->register();
