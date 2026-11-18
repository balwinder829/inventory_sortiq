<?php

if (! function_exists('format_currency')) {
    function format_currency($amount) {
        $symbol = config('app.currency_symbol');
        $format = config('app.currency_format');
        return $symbol . sprintf($format, $amount);
    }
}
