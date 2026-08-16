<?php

/**
 * One-off helper: rearrange graphify-out/import2.csv into the template layout
 * used by graphify-out/import.csv (and the Filament product importer):
 * Name, Price, Quantity, Category, Engine, Transmission, GVW, Store,
 * ECM Miles, YouTube URL, Extra Description, Image URL
 *
 * import2.csv mixes two different source column layouts and contains label/junk
 * columns (Td, Item, not url, URL) plus empty separator rows. This script maps
 * both layouts into the target columns and extracts the real GVW / ECM-miles
 * values even where the source mislabels the columns.
 */

$inFile = __DIR__ . '/import2.csv';
$outFile = __DIR__ . '/import2.formatted.csv';

$in = fopen($inFile, 'r');
$out = fopen($outFile, 'w');

$header = [
    'Name', 'Price', 'Quantity', 'Category', 'Engine', 'Transmission',
    'GVW', 'Store', 'ECM Miles', 'YouTube URL', 'Extra Description', 'Image URL',
];

fputcsv($out, $header, ',', '"', '\\');

$section = null;
$productRows = 0;

/**
 * Choose the best "Extra Description": prefer the longer candidate, but never
 * echo back the product Name itself (some source rows duplicate it there).
 */
$best = static function (string $primary, string $candidate, string $name): string {
    $name = trim($name);

    if ($candidate !== '' && strcasecmp(trim($candidate), $name) !== 0) {
        return strlen($candidate) > strlen($primary) ? $candidate : $primary;
    }

    return $primary;
};

while (($row = fgetcsv($in, null, ',', '"', '\\')) !== false) {
    // Drop any UTF-8 BOM + normalise the first cell for header detection.
    $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) ($row[0] ?? ''));

    $first = strtolower(trim($row[0]));

    if ($first === 'not url') {
        // Layout A header (all cells are column titles).
        $section = 'A';
        continue;
    }

    if ($first === 'image' && (isset($row[3]) && strtolower(trim($row[3])) === 'name')) {
        // Layout B header.
        $section = 'B';
        continue;
    }

    // Skip empty separator rows and anything outside a recognised section.
    $trimmed = array_map('trim', $row);
    if (count(array_filter($trimmed, fn ($v) => $v !== '')) === 0 || $section === null) {
        continue;
    }

    $r = array_pad(array_map('trim', $row), 15, '');

    $name = $price = $quantity = $category = $engine = $transmission = $gvw = $store = $ecmMiles = $youtube = $extraDescription = $imageUrl = '';

    if ($section === 'A') {
        // LAYOUT A columns:
        // not url(0) Name(1) Description(2) Engine(3) Transmission(4) gvw(5)
        // Td(6) Item(7) Store(8) Price(9) ecm_miles(10) Image_url(11)
        // extra_description(12) youtube_url(13)
        $name = $r[1];
        $price = $r[9];
        $engine = $r[3];
        $transmission = $r[4];
        $gvw = $r[5];
        $store = $r[8];
        $ecmMiles = $r[10];
        $youtube = $r[13];
        $imageUrl = $r[11];
        $extraDescription = $best($r[2], $r[12], $name);
    } else {
        // LAYOUT B columns:
        // Image(0) URL(1) Description(2) Name(3) extra_description(4) Engine(5)
        // Transmission(6) Td(7) gvw(8) Td(9) Item(10) Store(11) Price(12)
        // ecm_miles(13) youtube_url(14)
        //
        // The 7th column is a label prefix ("GVW:" or "ECM Miles (Hours):").
        // When it says "GVW:", column 8 holds the GVW and column 13 the miles.
        // When it says "ECM Miles (Hours):", column 8 holds the miles and there
        // is no GVW value in this row.
        $name = $r[3];
        $price = $r[12];
        $engine = $r[5];
        $transmission = $r[6];
        $store = $r[11];
        $youtube = $r[14];
        $imageUrl = $r[0];
        $extraDescription = $best($r[2], $r[4], $name);

        $label = strtolower(trim($r[7]));

        if (str_starts_with($label, 'gvw')) {
            $gvw = $r[8];
            $ecmMiles = $r[13];
        } else {
            $gvw = '';
            $ecmMiles = $r[8];
        }
    }

    // Normalise the price to a plain number (the importer tolerates "$12,000"
    // fine, but the template keeps plain numeric prices).
    $price = preg_replace('/[^0-9.]/', '', $price);

    fputcsv($out, [
        $name,
        $price,
        $quantity,
        $category,
        $engine,
        $transmission,
        $gvw,
        $store,
        $ecmMiles,
        $youtube,
        $extraDescription,
        $imageUrl,
    ], ',', '"', '\\');

    $productRows++;
}

fclose($in);
fclose($out);

echo "Wrote {$productRows} product rows to {$outFile}\n";