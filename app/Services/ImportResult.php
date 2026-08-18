<?php

namespace App\Services;

class ImportResult
{
    public int $rowsProcessed = 0;

    public int $createdRows = 0;

    public int $updatedRows = 0;

    public int $skippedRows = 0;

    public int $failedRows = 0;

    /**
     * @var array<int, array{row: int, errors: array<int, string>}>
     */
    public array $failures = [];

    /**
     * Header names in the uploaded file that we could not map to a product column.
     *
     * @var array<int, string>
     */
    public array $unknownColumns = [];

    public function totalRows(): int
    {
        return $this->rowsProcessed;
    }

    public function succeeded(): bool
    {
        return $this->failedRows === 0;
    }
}
