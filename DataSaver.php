<?php

class DataSaver
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function saveData($table, $data)
    {
        $tableName = 'exchange_rates';

        foreach ($data[0]['rates'] as $rate) {
            $currencyCode = $rate['code'];
            $exchangeRate = isset($rate['mid']) ? $rate['mid'] : 0;

            $query = "INSERT INTO $tableName (table_type, currency_code, rate)
                  VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE rate = ?";

            $statement = $this->connection->prepare($query);
            $statement->bind_param('ssds', $table, $currencyCode, $exchangeRate, $exchangeRate);

            if ($statement->execute() === false) {
                die("Error saving data: " . $this->connection->error);
            }

            $statement->close();
        }
    }

}

?>
