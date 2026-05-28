<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class AssetCodeGeneratorService
{
    /**
     * Generate a unique asset code for the given category.
     * Uses gap detection to reuse missing numbers in the sequence.
     * Thread-safe with database transactions and locking.
     */
    public function generate(Category $category): string
    {
        return DB::transaction(function () use ($category) {
            $prefix = strtoupper($category->prefix);

            // Get all existing numbers for this prefix (including soft-deleted to avoid conflicts)
            $existingNumbers = Asset::withTrashed()
                ->where('kode_asset', 'like', $prefix . '%')
                ->lockForUpdate()
                ->pluck('kode_asset')
                ->map(function ($code) use ($prefix) {
                    $numPart = substr($code, strlen($prefix));
                    return (int) $numPart;
                })
                ->sort()
                ->values()
                ->toArray();

            // Find the first gap in the sequence
            $nextNumber = $this->findFirstGap($existingNumbers);

            // Format the asset code
            $code = $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            // Verify uniqueness (additional safety check)
            while (Asset::withTrashed()->where('kode_asset', $code)->exists()) {
                $nextNumber++;
                $code = $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }

            return $code;
        });
    }

    /**
     * Find the first missing number in a sequence.
     * If no gaps exist, returns the next number after the highest.
     */
    private function findFirstGap(array $numbers): int
    {
        if (empty($numbers)) {
            return 1;
        }

        // Check for gaps starting from 1
        $expected = 1;
        foreach ($numbers as $number) {
            if ($number > $expected) {
                return $expected;
            }
            $expected = $number + 1;
        }

        // No gaps found, return next number
        return $expected;
    }

    /**
     * Preview the next code that would be generated for a category.
     */
    public function preview(Category $category): string
    {
        $prefix = strtoupper($category->prefix);

        $existingNumbers = Asset::withTrashed()
            ->where('kode_asset', 'like', $prefix . '%')
            ->pluck('kode_asset')
            ->map(function ($code) use ($prefix) {
                $numPart = substr($code, strlen($prefix));
                return (int) $numPart;
            })
            ->sort()
            ->values()
            ->toArray();

        $nextNumber = $this->findFirstGap($existingNumbers);

        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Validate that an asset code follows the correct format.
     */
    public function isValidFormat(string $code): bool
    {
        return (bool) preg_match('/^[A-Z]{2,10}\d{6}$/', $code);
    }
}
