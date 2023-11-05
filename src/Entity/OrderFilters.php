<?php

namespace App\Entity;

use DateInterval;
use DateTime;

class OrderFilters
{
    public ?string $search = null;

    public ?string $dateRange = null;
    
    public $types = [];

    public $status = [];

    public ?Warehouse $depot = null;

    public ?Customer $customer = null;

    public function getDateRange(): array
    {
        $range = [];
        if (!(is_null($this->dateRange) || trim($this->dateRange) === '')) 
        {
            $dateRangeArray = explode("to",$this->dateRange);
            foreach ($dateRangeArray as $dateStr) {
               $date = strtotime(trim($dateStr));
               array_push($range, date("d-m-y", $date)); // TODO: USe the config format
            }
        }
        return $range;
    }

    public function getFistRangeValue($range)
    {
        if (count($range) > 0) {
            return $range[0];
        }
    }

    public function getLastRangeValue($range)
    {
        if (count($range) == 0) {
            return $range[0];
        }
        return $range[1];
    }

}