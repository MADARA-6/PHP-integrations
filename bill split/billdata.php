<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input and set session variables
    session_start();
    $_SESSION["totalBill"] = $_POST["totalBill"];
    $_SESSION["numCustomers"] = $_POST["numCustomers"];
    $_SESSION["percentages"] = explode(',', $_POST["percentages"]);
    $_SESSION["tipAmount"] = $_POST["tipAmount"];

    // Redirect back to index.php
    header("Location: index.php");
exit;
}
?>