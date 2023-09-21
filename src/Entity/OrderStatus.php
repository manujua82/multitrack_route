<?php

namespace App\Entity;

enum OrderStatus: string
{
    case unschedule = "unschedule";
    case schedule = "schedule";
    case delivered = "delivered";
    case rejected = "rejected";
}