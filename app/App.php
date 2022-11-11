<?php

declare(strict_types=1);

function getTransactionFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) {
        if (is_dir($file)) continue;
        $files[] = $dirPath . $file;
    }

    return $files;
}

function getTransactions(string $fileName, ?callable $transtactionHandler = null): array
{

    if (!file_exists($fileName)) {
        trigger_error('File ' . $fileName . ' does not exist!', E_USER_ERROR);
    }

    $transtactions = [];
    $file = fopen($fileName, 'r');

    fgetcsv($file); //remove first line
    while (($transtaction = fgetcsv($file)) !== false) {
        if ($transtactionHandler !== null) {
            $transtaction = $transtactionHandler($transtaction);
        }
        $transtactions[] = $transtaction;
    }

    return $transtactions;
}

function extractTransaction(array $transtactionRow): array
{
    [$date, $checkNumber, $description, $amount] = $transtactionRow;

    $amount = (float) str_replace(['$', ','], '', $amount);

    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount
    ];
}

function calculateTotals($transtactions): array
{
    $total = ['netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    foreach ($transtactions as $transtaction) {
        $total['netTotal'] += $transtaction['amount'];

        if ($transtaction['amount'] >= 0) {
            $total['totalIncome'] += $transtaction['amount'];
        } else {
            $total['totalExpense'] += $transtaction['amount'];
        }
    }

    return $total;
}
