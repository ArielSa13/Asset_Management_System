<?php

if (!function_exists('format_rupiah')) {
    /**
     * Format number as Indonesian Rupiah.
     */
    function format_rupiah(?float $amount): string
    {
        if ($amount === null) return '-';
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('format_date_id')) {
    /**
     * Format date in Indonesian format.
     */
    function format_date_id(?\DateTimeInterface $date): string
    {
        if ($date === null) return '-';
        return $date->format('d M Y');
    }
}
