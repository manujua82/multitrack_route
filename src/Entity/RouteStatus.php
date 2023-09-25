<?php

namespace App\Entity;

enum RouteStatus: string
{
    case NONE = "none";
    case INITIATED = "initiated";
    case FINALIZED = "finalized";
}