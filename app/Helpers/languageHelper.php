<?php

function getLocalizedValue($value, $locale)
{
    return app()->isLocale('ar') ? $value->{$locale . '_ar'} : $value->{$locale . '_en'};
}
