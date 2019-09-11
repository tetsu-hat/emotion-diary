<?php
//オートロードを実装したファイルの読み込み
require '../automatic_execution.php';
//EmoDiaryApplicationの読み込み
require '../EmotionDiaryApplication.php';
//EmoDiaryApplicationをインスタンス化(引数はfalse)
$application = new EmotionDiaryApplication(false);
//EmoDiaryApplication実行
$application->run();
?>
