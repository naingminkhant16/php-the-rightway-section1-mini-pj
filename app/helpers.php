<?php
//helpers functions
declare(strict_types=1);

function dd(mixed $any)
{
    echo "<pre>";
    die(print_r($any));
}

function formatDollarAmount(float $amount): string
{
    $is_negative = $amount < 0;

    return ($is_negative ? '-' : '') . '$' . number_format(abs($amount), 2);
}

function formatDate(string $date): string
{
    return date('M j, Y', strtotime($date));
}
