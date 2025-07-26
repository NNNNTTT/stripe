<?php
    require_once 'config.php';
    require_once 'crud.php';

    //クレジットカード登録の処理
    if(isset($_POST['action']) && $_POST['action'] === 'create'){
        //クレジットカード登録のためのセットアップインテントを作成
        $setupintent = \Stripe\SetupIntent::create([
            'payment_method_types' => ['card'],
        ]);
        $client_secret = $setupintent->client_secret; //クライアントシークレット
        $_SESSION['client_secret'] = $client_secret; //クライアントシークレットをセッションに保存(フロント側で使うため)
        $_SESSION['client_secret_expires'] = time() + 60; //クライアントシークレットの有効期限
        header('Location: create_card.php');
        exit;
    }
    //与信の処理
    else if(isset($_POST['action']) && $_POST['action'] === 'authorize'){
        //与信のためのペイメントインテントを作成
        $payment_intent = \Stripe\PaymentIntent::create([
            'amount' => $_POST['item_price'],
            'currency' => 'jpy',
            'customer' => $_POST['stripe_customer_id'], //ストライプカスタマーID
            'payment_method' => $_POST['stripe_payment_method_id'], //ストライプペイメントメソッドID
            'confirm' => true,
            'off_session' => true,
            "capture_method" => "manual",     
        ]);
        //データベースに保存
        $data = [
            'name' => $_POST['item_name'],
            'price' => $_POST['item_price'],
            'stripe_pi_id' => $payment_intent->id,
            'stripe_customer_id' => $_POST['stripe_customer_id'],
            'yoshin_status' => 'success',
            'yoshin' => date('Y-m-d H:i:s'),
        ];
        create($data);
        header('Location: item_list.php');
        exit;
    }
    //決済確定の処理
    else if(isset($_POST['action']) && $_POST['action'] === 'capture'){
        //決済確定のためのペイメントインテントを取得
        $payment_intent = \Stripe\PaymentIntent::retrieve($_POST['stripe_pi_id']);
        //決済確定
        $payment_intent->capture();
        //確定データをデータベースに保存
        $data = [
            'id' => $_POST['id'],
            'kakutei' => date('Y-m-d H:i:s'),
        ];
        update($data);
        header('Location: item_list.php');
        exit;
    }
    //取消の処理
    else if(isset($_POST['action']) && $_POST['action'] === 'cancel'){
        //取消のためのペイメントインテントを取得
        $payment_intent = \Stripe\PaymentIntent::retrieve($_POST['stripe_pi_id']);
        //取消
        $payment_intent->cancel();
        //取り消しデータをデータベースに保存
        $data = [
            'id' => $_POST['id'],
            'cancel' => date('Y-m-d H:i:s'),
        ];
        update($data);
        header('Location: item_list.php');
        exit;
    }
    //削除の処理
    else if(isset($_POST['action']) && $_POST['action'] === 'delete'){
        $id = $_POST['id'];
        delete($id);
        header('Location: item_list.php');
        exit;
    }
    //クレジットカード登録後にカスタマーを紐付ける。（これを行わないとエラーになる）
    else if(isset($_GET['setup_intent_id'])){
        //セットアップインテントIDを取得
        $setup_intent_id = $_GET['setup_intent_id'];
        //セットアップインテントを取得
        $setup_intent = \Stripe\SetupIntent::retrieve($setup_intent_id);
        //ペイメントメソッドIDを取得
        $payment_method_id = $setup_intent->payment_method;
        //カスタマーを作成
        $customer = \Stripe\Customer::create([
            'payment_method' => $payment_method_id, //ペイメントメソッドにカスタマーを紐づける
        ]);

        $_SESSION['stripe_customer_id'] = $customer->id;
        $_SESSION['stripe_payment_method_id'] = $payment_method_id;

        //index.phpにリダイレクト
        header('Location: index.php');
        exit;
    }
    //それ以外の場合はindex.phpにリダイレクト
    else{
        header('Location: index.php');
        exit;
    }
?>