<?php

use App\Enums\OfferingType;

function getLocalizedValue($value, $locale)
{
    return app()->isLocale('ar') ? $value->{$locale . '_ar'} : $value->{$locale . '_en'};
}

function getLocalizedEnumValue($enumValue)
{
    $acceptedLanguage = app()->getLocale();

    return OfferingType::localizedValues()[$enumValue][$acceptedLanguage] ?? $enumValue;
}
