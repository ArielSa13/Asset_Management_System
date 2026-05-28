<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryDetectorService
{
    /**
     * Detect category from asset name using Category description keywords.
     * 
     * How it works:
     * - Each Category has a 'description' field that contains keywords (comma-separated)
     * - Example: Category "Peripherals" has description: "keyboard, headphone, microphone, mouse"
     * - When importing "Keyboard Logitech G Pro", it matches "keyboard" → Peripherals
     * 
     * Falls back to "Perangkat Lainnya" if no keyword matches.
     */
    public function detectCategory(string $assetName): ?Category
    {
        $assetNameLower = Str::lower($assetName);

        // Get all active categories with their descriptions
        $categories = Category::active()->get();

        foreach ($categories as $category) {
            // Skip categories without description
            if (empty($category->description)) {
                continue;
            }

            // Parse keywords from description (comma-separated)
            $keywords = array_map('trim', explode(',', strtolower($category->description)));

            foreach ($keywords as $keyword) {
                if (empty($keyword)) continue;
                
                // Check if asset name contains this keyword
                if (Str::contains($assetNameLower, $keyword)) {
                    return $category;
                }
            }
        }

        // Also try matching by category name or prefix directly
        foreach ($categories as $category) {
            $categoryNameLower = Str::lower($category->name);
            $prefixLower = Str::lower($category->prefix);
            
            if (Str::contains($assetNameLower, $categoryNameLower) || 
                Str::contains($assetNameLower, $prefixLower)) {
                return $category;
            }
        }

        // Fallback: assign to "Perangkat Lainnya" category
        return $this->getFallbackCategory();
    }

    /**
     * Get or create the fallback category "Perangkat Lainnya"
     */
    public function getFallbackCategory(): ?Category
    {
        // Try to find existing fallback category
        $fallback = Category::where('prefix', 'OTH')
            ->orWhere('name', 'Perangkat Lainnya')
            ->orWhere('name', 'Lainnya')
            ->orWhere('name', 'Others')
            ->orWhere('prefix', 'LAIN')
            ->first();

        if ($fallback) {
            return $fallback;
        }

        // Auto-create "Perangkat Lainnya" category if not exists
        return Category::create([
            'name' => 'Perangkat Lainnya',
            'prefix' => 'OTH',
            'description' => 'Kategori untuk asset yang tidak terdeteksi secara otomatis',
            'is_active' => true,
        ]);
    }
}
