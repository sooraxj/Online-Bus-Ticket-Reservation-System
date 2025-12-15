<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container styles */
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        /* Header styles */
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Form styles */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Input and select styles */
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Radio button styles */
        .form-check-input {
            margin-right: 10px;
        }

        /* Submit button styles */
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Total fare styles */
        .total-fare {
            background-color: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .total-fare h3 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        /* Alert styles */
        .alert {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .alert-danger {
            background-color: #ff6b6b;
            color: #fff;
            border: 1px solid #ff3333;
        }

        /* Custom styles for new card form */
        #newCardForm {
            display: none;
        }

        /* Payment option label styles */
        .form-check-label {
            color: #333;
        }

        /* Styling for card number and holder */
        input[name="cardNumber"],
        input[name="cardHolder"] {
            background-color: #f9f9f9;
        }

        /* Styling for CVV and expiration date */
        input[name="cvv"],
        input[name="expDate"] {
            background-color: #f9f9f9;
            width: 48%;
            display: inline-block;
            margin-right: 2%;
        }

        /* Card exists alert styles */
        #cardExistsAlert {
            background-color: #ff6b6b;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Details</h1>
        <div class="total-fare">
            <h3>Total Fare: $<?php echo number_format($totalFare, 2); ?></h3>
        </div>
        <form action="" method="post">
            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="paymentOption" value="existing" id="existingOption">
                    <label class="form-check-label" for="existingOption">Use Existing Card</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="paymentOption" value="new" id="newOption">
                    <label class="form-check-label" for="newOption">Add New Card</label>
                </div>
                <?php
                if (isset($_POST['submit']) && !isset($_POST['paymentOption'])) {
                    echo '<div class="alert alert-danger">Please select a payment option.</div>';
                }
                ?>
            </div>

            <div id="existingCardForm" class="form-group">
                <select class="form-control" name="existingCardSelect">
                    <option value="" selected disabled>Select Existing Card</option>
                    <?php
                    while ($row = $existingCardsResult->fetch_assoc()) {
                        $last4Digits = substr($row['Card_no'], -4);
                        echo "<option value='{$row['Card_id']}'>xxxx-xxxx-xxxx-$last4Digits</option>";
                    }
                    ?>
                </select>
                <input type="text" class="form-control" name="ecvv" maxlength="3" placeholder="CVV">
            </div>

            <div id="newCardForm">
                <input type="text" class="form-control" name="cardNumber" maxlength="20" placeholder="Card Number">
                <input type="text" class="form-control" name="cardHolder" placeholder="Card Holder">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="cvv" maxlength="3" placeholder="CVV">
                    </div>
                    <div class="col-md-6">
                        <input type="date" class="form-control" name="expDate" min="<?= date('Y-m-d'); ?>" placeholder="Expiration Date (YYYY-MM-DD)">
                    </div>
                </div>
            </div>
            <br>
            <div id="cardExistsAlert" class="alert alert-danger" style="display: none;">Card already exists.</div>

            <?php
            if (isset($cardExistsAlert)) {
                echo $cardExistsAlert;
            }
            ?>
            <input type="hidden" name="submit" value="true">
            <button type="submit" name="submit" class="btn btn-primary payment-button">Submit Payment</button>
        </form>
    </div>
</body>
</html>
