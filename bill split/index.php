<?php
session_start();
include 'billData.php';

$error = ''; // Define $error

function splitBill($totalAmount, $numCustomers, $percentages = null, $tipAmount = 0) {
    $splits = array();
    $totalAmountWithTip = $totalAmount + $tipAmount;
    if ($percentages == null) {
        // Split equally
        $splitAmount = $totalAmountWithTip / $numCustomers;
        for ($i = 0; $i < $numCustomers; $i++) {
            $splits[] = $splitAmount;
        }
    } else {
        // Split according to percentages
        for ($i = 0; $i < $numCustomers; $i++) {
            $splits[] = $totalAmountWithTip * ($percentages[$i] / 100);
        }
    }
    return array('splits' => $splits, 'total' => $totalAmountWithTip);
}


if (isset($_SESSION["totalBill"]) && isset($_SESSION["numCustomers"]) && isset($_SESSION["percentages"]) && isset($_SESSION["tipAmount"])) {
    $totalBill = floatval($_SESSION["totalBill"]);
    $numCustomers = intval($_SESSION["numCustomers"]);
    $percentages = array_map('floatval', $_SESSION["percentages"]);
    $tipAmount = empty($_SESSION["tipAmount"]) ? 0 : floatval($_SESSION["tipAmount"]);

    if ($totalBill <= 0 || $numCustomers <= 0 || array_sum($percentages) != 100 || count($percentages) != $numCustomers) {
        $error = 'Invalid input. Please ensure that the total bill and number of customers are positive numbers, the percentages add up to 100, and the number of percentages matches the number of customers.';
    } else {
        $result = splitBill($totalBill, $numCustomers, $percentages, $tipAmount);
        $billSplits = $result['splits'];
        $totalBillWithTip = $result['total'];
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .error {
            color: red;
        }
        .custom-input {
            width: 300px; /* Adjust this value as needed */
            max-width: 100%; /* This ensures the input doesn't overflow on small screens */
        }
        .center-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
<div class="center-content">
    <h2 class="mb-3">Split Bill</h2>
   
    <form method="post" action="billData.php">
        <div class="form-group custom-input">
            <label for="totalBill">Total Bill:</label>
            <input type="text" class="form-control" id="totalBill" name="totalBill">
        </div>
        <div class="form-group custom-input">
            <label for="numCustomers">Number of Customers:</label>
            <input type="text" class="form-control" id="numCustomers" name="numCustomers">
        </div>
        <div class="form-group custom-input">
            <label for="percentages">Percentages (comma-separated):</label>
            <input type="text" class="form-control" id="percentages" name="percentages">
        </div>
        <div class="form-group custom-input">
            <label for="tipAmount">Tip Amount:</label>
            <input type="text" class="form-control" id="tipAmount" name="tipAmount">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <p class="error mt-3"><?php echo $error; ?></p>

    <?php
    if (isset($billSplits)) {
        for ($i = 0; $i < $_SESSION["numCustomers"]; $i++) {
            echo "<p>Customer " . ($i + 1) . ": $" . number_format($billSplits[$i], 2) . "</p>";
        }
        echo "<p>Total Bill (with tip): $" . number_format($totalBillWithTip, 2) . "</p>";
    }
    ?>
    </div>

</body>
</html>