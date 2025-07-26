<?php
    require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品購入</title>
</head>
<style>
    body {
        background-color: #f0f0f0;
    }
    .item {
        width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
    }
    .item h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .item table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .item table td {
        padding: 10px 20px;
        border: 1px solid #ccc;
    }
    .button{
        width: 200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .button button {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #000;
        color: #fff;
        cursor: pointer;
    }
    .button button:hover {
        background-color: #333;
    }
    .header button{
        background-color: #000;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }
    .header button:hover {
        background-color: #333;
    }
</style>
<body>   
    <form action="card.php" method="POST">
        <div class="item">
            <div class="header">
                <button type="button" onclick="location.href='item_list.php'">購入一覧</button>
            </div>
            <h2>商品購入</h2>
            <table>
                <tr>
                    <td>商品名</td>
                    <td>りんご</td>
                </tr>
                <tr>
                    <td>価格</td>
                    <td>100円</td>
                </tr>
            </table>
            <input type="hidden" name="item_name" value="りんご">
            <input type="hidden" name="item_price" value="100">
            <div class="button">
                <!-- クレジットカード登録済みかどうかの判定 -->
                <?php if(isset($_SESSION['stripe_customer_id']) && isset($_SESSION['stripe_payment_method_id'])){ ?>
                    <input type="hidden" name="stripe_payment_method_id" value="<?php echo $_SESSION['stripe_payment_method_id']; ?>">
                    <input type="hidden" name="stripe_customer_id" value="<?php echo $_SESSION['stripe_customer_id']; ?>">
                    <p>クレジットカード登録済み</p>
                    <button type="submit" name="action" value="authorize">購入</button>
                    <?php unset($_SESSION['stripe_customer_id']); ?>
                    <?php unset($_SESSION['stripe_payment_method_id']); ?>
                <?php }else{ ?>
                    <button type="submit" name="action" value="create">クレジットカード登録</button>
                    <button type="submit" name="action" value="authorize">購入</button>
                <?php } ?>
            </div>
        </div>

    </form>
</body>
</html>