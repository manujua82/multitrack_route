<?php

namespace App\Entity;

enum OrderStatus: string
{
    case UNSCHEDULE = "unschedule";
    case SCHEDULE= "schedule";
    case DELIVERED = "delivered";
    case REJECTED = "rejected";
}