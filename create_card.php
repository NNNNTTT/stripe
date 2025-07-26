<?php
    require_once 'config.php';
    //もしセッションが切れていたらindex.phpにリダイレクト
    if(isset($_SESSION['client_secret_expires']) && $_SESSION['client_secret_expires'] < time()){
        unset($_SESSION['client_secret']);
        unset($_SESSION['client_secret_expires']);
        header('Location: index.php');
        exit;
    }
    $client_secret = $_SESSION['client_secret'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>クレジットカード登録</title>
</head>
<style>
    body {
        background-color: #f0f0f0;
    }
    .card-form {
        width: 400px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
    }
    .card-form h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    #card-element {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }
    #card-number, #card-expiry, #card-cvc {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    button {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #000;
        color: #fff;
        cursor: pointer;
    }
    button:hover {
        background-color: #333;
    }
    #error-message {
        color: red;
        text-align: center;
        margin-top: 10px;
    }
    
</style>
<body>
    <script src="https://js.stripe.com/v3/"></script>
    <form action="" method="POST">
        <div class="card-form">
            <h2>クレジットカード登録</h2>
            <div id="card-element">
                <div id="card-number"></div>
                <div id="card-expiry"></div>
                <div id="card-cvc"></div>
            </div>
            <button type="submit">登録</button>
            <p id="error-message"></p>
        </div>

    </form>
    <script>
        const clientSecret = "<?php echo $client_secret; ?>";
        console.log(clientSecret);

        const stripe = Stripe('<?php echo $stripePublishableKey; ?>');
        const elements = stripe.elements();

        const cardNumber = elements.create('cardNumber');
        cardNumber.mount('#card-number');
        const cardExpiry = elements.create('cardExpiry');
        cardExpiry.mount('#card-expiry');
        const cardCvc = elements.create('cardCvc');
        cardCvc.mount('#card-cvc');

        const cardForm = document.querySelector('form');
        cardForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret,
                {
                    payment_method: {
                        card: cardNumber,
                    },
                    return_url: 'https://localhost/stripe/card.php',
                },
            );

            if (error) {
                console.error(error);
                document.getElementById('error-message').textContent = error.message;
            } else {
                console.log(setupIntent);
                window.location.href = 'card.php?setup_intent_id=' + setupIntent.id + '&setup_intent_status=' + setupIntent.status;
            }
        });
    </script>
</body>
</html>