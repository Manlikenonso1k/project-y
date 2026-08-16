<?php

/**
 * One-off helper: fill the Category column (index 3) with a default value
 * wherever it is empty, so the file passes the importer's category requirement.
 *
 * Usage: php fill_category.php <category> <input.csv> <output.csv>
 */

$defaultCategory = $argv[1] ?? 'Trucks';
$inFile = $argv[2] ?? __DIR__.'/combined data - Combined Data.csv';
$outFile = $argv[3] ?? __DIR__.'/combined data - Combined Data.fixed.csv';

$in = fopen($inFile, 'r');
$out = fopen($outFile, 'w');

$isFirst = true;
$filled = 0;
$total = 0;

while (($row = fgetcsv($in, null, ',', '"', '\\')) !== false) {
    if ($isFirst) {
        fputcsv($out, $row, ',', '"', '\\');
        $isFirst = false;

        continue;
    }

    // Skip fully empty separator rows, but keep them out of the output anyway.
    if (count(array_filter(array_map('trim', $row))) === 0) {
        continue;
    }

    $row[3] = trim($row[3] ?? '') === '' ? $defaultCategory : trim($row[3]);

    if ($row[3] === $defaultCategory) {
        $filled++;
    }

    $total++;

    fputcsv($out, $row, ',', '"', '\\');
}

fclose($in);
fclose($out);

echo "Filled category '{$defaultCategory}' on {$filled} of {$total} data rows -> {$outFile}\n";