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
     * Priority System:
     * - Uses EXACT WORD MATCH with longest keyword (more specific wins)
     * - "Adaptor Notebook" → matches "adaptor" (at start, score: 157) > "notebook" (middle, score: 58)
     * - "Monopod" → NO match with "mon" from "monitor" (not exact word) ✓
     * 
     * Scoring:
     * - Base: keyword length (longer = more specific)
     * - +100: keyword at START of name
     * - +50: always added (all matches are exact words)
     * 
     * Falls back to "Perangkat Lainnya" if no keyword matches.
     */
    public function detectCategory(string $assetName): ?Category
    {
        $assetNameLower = Str::lower($assetName);

        // Check for ACCESSORY indicators first (highest priority)
        // These words indicate the item is an accessory, not the main device
        $accessoryIndicators = [
            'tas', 'softcase', 'soft case', 'hardcase', 'hard case', 'case',
            'backpack', 'ransel', 'bag', 'pouch', 'sarung', 'cover',
            'holder', 'stand', 'bracket', 'mount', 'strap', 'tali'
        ];
        
        foreach ($accessoryIndicators as $indicator) {
            if (preg_match('/\b' . preg_quote($indicator, '/') . '\b/i', $assetNameLower)) {
                // This is an accessory, try to find "Accessories" or similar category
                $accessoryCategory = $this->getAccessoryCategory();
                if ($accessoryCategory) {
                    return $accessoryCategory;
                }
                // If no accessory category, continue with normal detection
                // but penalize non-accessory categories
                break;
            }
        }

        // Get all active categories with their descriptions
        $categories = Category::active()->get();

        // Build a match scoring system: [category => [matched_keyword => score]]
        $matches = [];

        foreach ($categories as $category) {
            // Skip categories without description
            if (empty($category->description)) {
                continue;
            }

            // Parse keywords from description (comma-separated)
            $keywords = array_map('trim', explode(',', strtolower($category->description)));

            foreach ($keywords as $keyword) {
                if (empty($keyword)) continue;
                
                // ONLY match exact words using word boundaries
                // This prevents "monopod" from matching "mon" in "monitor"
                if (!preg_match('/\b' . preg_quote($keyword, '/') . '\b/i', $assetNameLower)) {
                    continue; // Skip if not exact word match
                }
                
                // Score based on keyword length (longer = more specific)
                $score = strlen($keyword);
                
                // Bonus score if keyword appears at the start
                if (Str::startsWith($assetNameLower, $keyword)) {
                    $score += 100; // High bonus for starting with keyword
                }
                
                // Already exact word match, add bonus
                $score += 50; // Bonus for exact word match
                
                // Store the match with highest score for this category
                if (!isset($matches[$category->id]) || $score > $matches[$category->id]['score']) {
                    $matches[$category->id] = [
                        'category' => $category,
                        'keyword' => $keyword,
                        'score' => $score
                    ];
                }
            }
        }

        // If we have matches, return the one with highest score
        if (!empty($matches)) {
            usort($matches, function($a, $b) {
                return $b['score'] - $a['score'];
            });
            
            return $matches[0]['category'];
        }

        // Also try matching by category name or prefix directly (with exact word match)
        $nameMatches = [];
        foreach ($categories as $category) {
            $categoryNameLower = Str::lower($category->name);
            $prefixLower = Str::lower($category->prefix);
            
            // Check category name with exact word boundary
            if (preg_match('/\b' . preg_quote($categoryNameLower, '/') . '\b/i', $assetNameLower)) {
                $score = strlen($categoryNameLower);
                if (Str::startsWith($assetNameLower, $categoryNameLower)) {
                    $score += 100;
                }
                $score += 50; // Exact word bonus
                $nameMatches[] = ['category' => $category, 'score' => $score];
            }
            // Check prefix with exact word boundary
            elseif (preg_match('/\b' . preg_quote($prefixLower, '/') . '\b/i', $assetNameLower)) {
                $score = strlen($prefixLower);
                if (Str::startsWith($assetNameLower, $prefixLower)) {
                    $score += 100;
                }
                $score += 50; // Exact word bonus
                $nameMatches[] = ['category' => $category, 'score' => $score];
            }
        }
        
        if (!empty($nameMatches)) {
            usort($nameMatches, function($a, $b) {
                return $b['score'] - $a['score'];
            });
            
            return $nameMatches[0]['category'];
        }

        // Fallback: assign to "Perangkat Lainnya" category
        return $this->getFallbackCategory();
    }

    /**
     * Get the Accessories/Pelengkap category
     */
    public function getAccessoryCategory(): ?Category
    {
        // Try to find existing accessory category
        $accessory = Category::where(function($q) {
            $q->where('name', 'like', '%accessories%')
              ->orWhere('name', 'like', '%pelengkap%')
              ->orWhere('name', 'like', '%aksesoris%')
              ->orWhere('prefix', 'ACC')
              ->orWhere('prefix', 'SFC'); // Soft case prefix
        })->first();

        if ($accessory) {
            return $accessory;
        }

        // Auto-create "Accessories" category if not exists
        return Category::create([
            'name' => 'Accessories',
            'prefix' => 'ACC',
            'description' => 'tas, softcase, hardcase, bag, backpack, ransel, case, cover, holder, stand, bracket, mount, strap, tali, sarung, pouch',
            'is_active' => true,
        ]);
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
