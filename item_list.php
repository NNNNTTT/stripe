<?php
    require_once 'crud.php';
    $orders = read();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入一覧</title>
</head>
<style>
    body{
        background-color: #f0f0f0;
    }
    .container{
        width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px 20px 40px 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
    }
    h2{
        text-align: center;
    }
    table{
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }
    th{
        background-color: #f0f0f0;
        border: 1px solid #ccc;
    }
    td{
        border: 1px solid #ccc;
        padding: 10px 5px;
    }
    table td button{
        background-color: #000;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }
    table td button:hover {
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
    <div class="container">
    <div class="header">
        <button type="button" onclick="location.href='index.php'">商品購入</button>
    </div>
    <h2>購入一覧</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>商品名</th>
                <th>価格</th>
                <th>決済</th>
                <th>取消</th>
                <th>削除</th>
            </tr>
            <?php foreach($orders as $order){ ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['name']; ?></td>
                    <td><?php echo $order['price']; ?></td>
                    <?php if($order['kakutei'] !== '0000-00-00'){?>
                        <td>決済確定済</td>
                    <?php }else if($order['cancel'] !== '0000-00-00'){ ?>
                        <td></td>
                    <?php }else{ ?>
                        <td>
                            <form action="card.php" method="post">
                                <button type="submit" name="action" value='capture'>決済確定</button>
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="stripe_pi_id" value="<?php echo $order['stripe_pi_id']; ?>">                            
                            </form>
                        </td>
                    <?php } ?>
                    <?php if($order['kakutei'] !== '0000-00-00'){?>
                        <td></td>
                    <?php }else if($order['cancel'] !== '0000-00-00'){ ?>
                        <td>取消済</td>
                    <?php }else{ ?>
                        <td>
                            <form action="card.php" method="post">
                                <button type="submit" name="action" value='cancel'>取消</button>
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="stripe_pi_id" value="<?php echo $order['stripe_pi_id']; ?>">
                            </form>
                        </td>
                    <?php } ?>
                    <td>
                        <form action="card.php" method="post">
                            <button type="submit" name="action" value='delete'>削除</button>
                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>