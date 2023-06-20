<?php

class ApiNBP
{
    public function fetchData($table)
    {
        $url = "http://api.nbp.pl/api/exchangerates/tables/$table?format=json";
        $data = file_get_contents($url);
        return json_decode($data, true);
    }

}

?>