<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PHP_APINBP</title>
    <style>
        form, table {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<?php
require "InitializeApp.php";
$app = new InitializeApp();
$app->initialize();
$app->getDataViewer()->showCurrenciesInConverter();
?>
<h1>Konwerter walut</h1>
<form method="POST" action="">
    <label for="amount">Kwota:</label>
    <input type="number" name="amount" id="amount" required>
    <br>

    <label for="from_currency">Waluta do przewalutowania:</label>
    <select name="from_currency" id="from_currency" required>
        <option value="PLN">PLN</option>
        <?php
        foreach ($app->getDataViewer()->showCurrenciesInConverter() as $currency) {
            echo "<option value='$currency'>$currency</option>";
        }
        ?>
    </select>
    <br>

    <label for="to_currency">Waluta na którą przeliczyć:</label>
    <select name="to_currency" id="to_currency" required>
        <option value="PLN">PLN</option>
        <?php
        foreach ($app->getDataViewer()->showCurrenciesInConverter() as $currency) {
            echo "<option value='$currency'>$currency</option>";
        }
        ?>
    </select>
    <br>

    <input type="submit" name="submit" value="Przelicz">
</form>
<?php
$app->getCurrencyConverter()->handleCurrencyConversion();
$app->getDataViewer()->displayLastFiveConversions();
$app->getDataViewer()->displayAllRates();
?>
</body>
</html>
