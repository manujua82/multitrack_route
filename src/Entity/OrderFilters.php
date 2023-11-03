<?php

namespace App\Entity;

class OrderFilters
{
    public ?string $search = null;

    public ?string $dateRange = null;
    
    public $types = [];

    public $status = [];

    public ?Warehouse $depot = null;

    public ?Customer $customer = null;

}