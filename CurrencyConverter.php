<?php
class CurrencyConverter
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function convertCurrency($amount, $fromCurrency, $toCurrency)
    {
        $tableName = 'exchange_rates';

        if ($fromCurrency === 'PLN') {
            return $this->convertFromPLN($amount, $toCurrency);
        } elseif ($toCurrency === 'PLN') {
            return $this->convertToPLN($amount, $fromCurrency);
        } else {
            return $this->convertBetweenCurrencies($amount, $fromCurrency, $toCurrency);
        }
    }

    private function convertFromPLN($amount, $toCurrency)
    {
        $tableName = 'exchange_rates';

        $query = "SELECT rate FROM $tableName WHERE table_type = 'A' AND currency_code = '$toCurrency'";
        $result = $this->connection->query($query);

        if ($result->num_rows === 0) {
            throw new Exception("Nie znaleziono kursu dla waluty '$toCurrency'.");
        }

        $toRate = $result->fetch_assoc()['rate'];
        $convertedAmount = $amount / $toRate;

        $this->saveConversionToDatabase($amount, 'PLN', $toCurrency, $convertedAmount);

        return number_format($convertedAmount, 2);
    }

    private function convertToPLN($amount, $fromCurrency)
    {
        $tableName = 'exchange_rates';

        $query = "SELECT rate FROM $tableName WHERE table_type = 'A' AND currency_code = '$fromCurrency'";
        $result = $this->connection->query($query);

        if ($result->num_rows === 0) {
            throw new Exception("Nie znaleziono kursu dla waluty '$fromCurrency'.");
        }

        $fromRate = $result->fetch_assoc()['rate'];
        $convertedAmount = $amount * $fromRate;

        $this->saveConversionToDatabase($amount, $fromCurrency, 'PLN', $convertedAmount);

        return number_format($convertedAmount, 2);
    }

    private function convertBetweenCurrencies($amount, $fromCurrency, $toCurrency)
    {
        $tableName = 'exchange_rates';

        $query = "SELECT rate FROM $tableName WHERE table_type = 'A' AND currency_code = '$fromCurrency'";
        $result = $this->connection->query($query);

        if ($result->num_rows === 0) {
            throw new Exception("Nie znaleziono kursu dla waluty '$fromCurrency'.");
        }

        $fromRate = $result->fetch_assoc()['rate'];

        $query = "SELECT rate FROM $tableName WHERE table_type = 'A' AND currency_code = '$toCurrency'";
        $result = $this->connection->query($query);

        if ($result->num_rows === 0) {
            throw new Exception("Nie znaleziono kursu dla waluty '$toCurrency'.");
        }

        $toRate = $result->fetch_assoc()['rate'];

        $amountInPLN = $amount * $fromRate;
        $convertedAmount = $amountInPLN / $toRate;

        $this->saveConversionToDatabase($amount, $fromCurrency, $toCurrency, $convertedAmount);

        return number_format($convertedAmount, 2);
    }

    private function saveConversionToDatabase($amount, $fromCurrency, $toCurrency, $convertedAmount)
    {
        $tableName = 'converts';
        $query = "INSERT INTO $tableName (amount, from_currency, to_currency, converted_amount) 
                  VALUES ($amount, '$fromCurrency', '$toCurrency', $convertedAmount)";
        $this->connection->query($query);
    }

    public function handleCurrencyConversion()
    {
        if (isset($_POST['submit'])) {
            $amount = $_POST['amount'];
            $fromCurrency = $_POST['from_currency'];
            $toCurrency = $_POST['to_currency'];

            if ($fromCurrency === $toCurrency) {
                echo "<p>Waluty nie mogą być takie same!</p>";
                return;
            }

            try {
                $convertedAmount = $this->convertCurrency($amount, $fromCurrency, $toCurrency);
                echo "<p>$amount $fromCurrency = $convertedAmount $toCurrency</p>";
            } catch (Exception $e) {
                echo "<p>Wystąpił błąd: " . $e->getMessage() . "</p>";
            }
        }
    }
}

?>