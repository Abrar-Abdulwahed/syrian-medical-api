<?php

function isTimePassed($period, $timestamp){
    return now()->subMinutes($period)->gt($timestamp);
}