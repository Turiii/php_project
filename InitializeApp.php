<?php
require "Connection.php";
require "ApiNBP.php";
require "DataSaver.php";
require "DataViewer.php";
require "CurrencyConverter.php";
require "TableCreator.php";

class InitializeApp
{
    private $connection;
    private $creator;
    private $apiNBP;
    private $dataSaver;
    private $dataViewer;
    private $currencyConverter;

    public function __construct()
    {
        $this->connection = new Connection();
        $this->creator = new TableCreator($this->connection->getConnection());
        $this->apiNBP = new ApiNBP();
        $this->dataSaver = new DataSaver($this->connection->getConnection());
        $this->dataViewer = new DataViewer($this->connection->getConnection());
        $this->currencyConverter = new CurrencyConverter($this->connection->getConnection());
    }

    public function initialize()
    {
        $this->creator->createConvertsTable();
        $this->creator->createExchangeRatesTable();

        $tables = ['A', 'B', 'C'];
        foreach ($tables as $table) {
            $data = $this->apiNBP->fetchData($table);
            $this->dataSaver->saveData($table, $data);
        }
    }

    public function getCurrencyConverter()
    {
        return $this->currencyConverter;
    }

    public function getDataViewer()
    {
        return $this->dataViewer;
    }
}

?>
