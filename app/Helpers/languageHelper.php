<?php

use App\Enums\OfferingType;

function getLocalizedEnumValue($enum, $lang)
{
    return $enum::localizedValues()[$enum->value][$lang] ?? $enum->value;
}
