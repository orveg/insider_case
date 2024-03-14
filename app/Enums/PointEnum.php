<?php

namespace App\Enums;
enum PointEnum :int
{
    const WON_POINT = 3;
    const LOST_POINT = 0;
    const DRAWN_POINT = 1;
    const MAX_GOAL = 4;
}
