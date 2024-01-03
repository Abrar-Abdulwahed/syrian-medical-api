<?php

function generateRandomNumber($digits)
{
    $min = pow(10, $digits - 1);
    $max = pow(10, $digits) - 1;
    return rand($min, $max);
}