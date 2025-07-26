<?php
// Stripe SDKの読み込み
require_once 'vendor/autoload.php';
session_start();

// .envファイルの読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// データベース設定を環境変数から取得
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);

try{
    function getPDO(){
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        return $pdo;
    }
}catch(PDOException $e){
    echo '接続に失敗しました: ' . $e->getMessage();
    exit;
}

// Stripe APIキーの設定（環境変数から取得）
$stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'];
$stripePublishableKey = $_ENV['STRIPE_PUBLIC_KEY'];

// Stripeクライアントの初期化
\Stripe\Stripe::setApiKey($stripeSecretKey);

// エラーハンドリングの設定
\Stripe\Stripe::setApiVersion('2023-10-16'); // 最新のAPIバージョンを使用
?> 