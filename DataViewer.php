<?php

class DataViewer
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function displayAllRates()
    {
        $tableName = 'exchange_rates';
        $query = "SELECT * FROM $tableName";

        $result = $this->connection->query($query);
        $data = $result->fetch_all(MYSQLI_ASSOC);

        if (empty($data)) {
            echo "Brak danych do wyświetlenia.";
            return;
        }

        echo "<table>";
        echo "<tr><th>Table Type</th><th>Currency Code</th><th>Rate</th></tr>";

        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>" . $row['table_type'] . "</td>";
            echo "<td>" . $row['currency_code'] . "</td>";
            echo "<td>" . $row['rate'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

    public function showCurrenciesInConverter()
    {
        $query = "SELECT currency_code FROM exchange_rates WHERE table_type = 'A' ORDER BY currency_code ASC";
        $result = $this->connection->query($query);

        $currencies = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $currencies[] = $row['currency_code'];
            }
        }

        return $currencies;
    }

    public function displayLastFiveConversions(){
        // Wyświetlanie 5 najnowszych wpisów z tabeli "converts"
        $query = "SELECT * FROM converts ORDER BY id DESC LIMIT 5";
        $result = $this->connection->query($query);

        if ($result->num_rows > 0) {
            echo "<h2>Najnowsze przeliczenia:</h2>";
            echo "<table>";
            echo "<tr><th>Kwota</th><th>Waluta z</th><th>Waluta na</th><th>Wynik</th></tr>";
            while ($row = $result->fetch_assoc()) {
                $amount = $row['amount'];
                $fromCurrency = $row['from_currency'];
                $toCurrency = $row['to_currency'];
                $convertedAmount = $row['converted_amount'];
                echo "<tr>";
                echo "<td>$amount</td>";
                echo "<td>$fromCurrency</td>";
                echo "<td>$toCurrency</td>";
                echo "<td>$convertedAmount</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
}

?>