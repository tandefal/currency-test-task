<?php

namespace App\Models;

use PDO;

class CurrencyRate
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function saveRatesToDatabase(array $rates)
    {
        $this->db->beginTransaction();
        try {
            $this->clearCache();
            $query = "INSERT INTO currency_rates (currency_code, num_code, char_code, nominal, name, value, vunit_rate) VALUES (:currency_code, :num_code, :char_code, :nominal, :name, :value, :vunit_rate)";
            $statement = $this->db->prepare($query);
            foreach ($rates as $rate) {
                $statement->execute($rate);
            }
            $this->db->commit();
        } catch (\PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function clearCache()
    {
        $query = "DELETE FROM currency_rates";
        $this->db->exec($query);
    }

    public function getAllRates()
    {
        $query = "SELECT * FROM currency_rates WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRate($currencyCode)
    {
        $query = "SELECT * FROM currency_rates WHERE currency_code = :currency_code AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $statement = $this->db->prepare($query);
        $statement->execute(['currency_code' => $currencyCode]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
