<?php
class TableCreator
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->createExchangeRatesTable();
        $this->createConvertsTable();
    }

    public function createExchangeRatesTable()
    {
        $tableName = 'exchange_rates';
        $query = "CREATE TABLE IF NOT EXISTS $tableName (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            table_type VARCHAR(1) NOT NULL,
            currency_code VARCHAR(3) NOT NULL,
            rate DECIMAL(10, 4) NOT NULL,
            UNIQUE KEY unique_key (table_type, currency_code)
        )";

        if ($this->connection->query($query) === false) {
            die("Error creating table: " . $this->connection->error);
        }
    }

    public function createConvertsTable()
    {
        $tableName = 'converts';
        $query = "CREATE TABLE IF NOT EXISTS $tableName (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            amount DECIMAL(10, 2) NOT NULL,
            from_currency VARCHAR(3) NOT NULL,
            to_currency VARCHAR(3) NOT NULL,
            converted_amount DECIMAL(10, 2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($this->connection->query($query) === false) {
            die("Error creating table: " . $this->connection->error);
        }
    }
}

?>