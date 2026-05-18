<?php

if (!function_exists('currency_symbol')) {

    function currency_symbol()
    {
        $currency = auth()->user()->currency ?? 'USD';

        return match ($currency) {
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            'INR' => '₹',
            'LKR' => 'Rs',
            default => '$',
        };
    }
}
