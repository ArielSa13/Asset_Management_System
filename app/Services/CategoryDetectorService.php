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
     * - Uses longest keyword match first (more specific wins)
     * - "Adaptor Notebook" → matches "adaptor" (8 chars) > "notebook" (8 chars)
     * - First match with longest keyword wins
     * 
     * Falls back to "Perangkat Lainnya" if no keyword matches.
     */
    public function detectCategory(string $assetName): ?Category
    {
        $assetNameLower = Str::lower($assetName);

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
                
                // Check if asset name contains this keyword
                if (Str::contains($assetNameLower, $keyword)) {
                    // Score based on keyword length (longer = more specific)
                    $score = strlen($keyword);
                    
                    // Bonus score if keyword appears at the start
                    if (Str::startsWith($assetNameLower, $keyword)) {
                        $score += 100; // High bonus for starting with keyword
                    }
                    
                    // Bonus score if keyword is an exact word (not part of another word)
                    if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $assetNameLower)) {
                        $score += 50; // Bonus for exact word match
                    }
                    
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
        }

        // If we have matches, return the one with highest score
        if (!empty($matches)) {
            usort($matches, function($a, $b) {
                return $b['score'] - $a['score'];
            });
            
            return $matches[0]['category'];
        }

        // Also try matching by category name or prefix directly (with scoring)
        $nameMatches = [];
        foreach ($categories as $category) {
            $categoryNameLower = Str::lower($category->name);
            $prefixLower = Str::lower($category->prefix);
            
            if (Str::contains($assetNameLower, $categoryNameLower)) {
                $score = strlen($categoryNameLower);
                if (Str::startsWith($assetNameLower, $categoryNameLower)) {
                    $score += 100;
                }
                $nameMatches[] = ['category' => $category, 'score' => $score];
            } elseif (Str::contains($assetNameLower, $prefixLower)) {
                $score = strlen($prefixLower);
                if (Str::startsWith($assetNameLower, $prefixLower)) {
                    $score += 100;
                }
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
