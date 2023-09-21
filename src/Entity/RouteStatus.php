<?php

namespace App\Entity;

enum RouteStatus: string
{
    case none = "none";
    case initiated = "initiated";
    case finalized = "finalized";
}