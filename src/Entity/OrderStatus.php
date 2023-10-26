<?php

namespace App\Entity;

enum OrderStatus: string
{
    case UNSCHEDULE = "unschedule";
    case SCHEDULE= "schedule";
    case IN_PROGRESS="inProgress";
    case DELIVERED = "delivered";
    case REJECTED = "rejected";
    case PARTIALLY = "partially";
    case NO_DELIVERED = "noDelivered";
}