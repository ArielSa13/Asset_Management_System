<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryDetectorService
{
    /**
     * Category keywords mapping
     * Format: 'keyword' => 'category_prefix'
     */
    private array $categoryKeywords = [
        // Komputer & Laptop
        'laptop' => ['KOM', 'LAP', 'LAPTOP', 'KOMPUTER'],
        'notebook' => ['KOM', 'LAP', 'LAPTOP', 'KOMPUTER'],
        'computer' => ['KOM', 'LAP', 'LAPTOP', 'KOMPUTER'],
        'pc' => ['KOM', 'LAP', 'LAPTOP', 'KOMPUTER'],
        'macbook' => ['KOM', 'LAP', 'LAPTOP', 'KOMPUTER'],
        
        // Printer & Scanner
        'printer' => ['PRT', 'PRINTER'],
        'scanner' => ['SCN', 'SCANNER', 'SCAN'],
        'fotocopy' => ['PRT', 'PRINTER'],
        'fotokopi' => ['PRT', 'PRINTER'],
        
        // Monitor & Display
        'monitor' => ['MON', 'MONITOR'],
        'display' => ['MON', 'MONITOR'],
        'lcd' => ['MON', 'MONITOR'],
        'led' => ['MON', 'MONITOR'],
        'screen' => ['MON', 'MONITOR'],
        
        // Keyboard & Mouse
        'keyboard' => ['PER', 'PERIPHERALS', 'KEYBOARD'],
        'mouse' => ['PER', 'PERIPHERALS', 'MOUSE'],
        'mice' => ['PER', 'PERIPHERALS', 'MOUSE'],
        
        // Headset & Audio
        'headset' => ['AUD', 'AUDIO', 'HEADSET'],
        'headphone' => ['AUD', 'AUDIO', 'HEADSET'],
        'speaker' => ['AUD', 'AUDIO', 'SPEAKER'],
        'microphone' => ['AUD', 'AUDIO', 'MIC'],
        'mic' => ['AUD', 'AUDIO', 'MIC'],
        
        // Networking
        'router' => ['NET', 'NETWORK', 'ROUTER'],
        'switch' => ['NET', 'NETWORK', 'SWITCH'],
        'modem' => ['NET', 'NETWORK', 'MODEM'],
        'access point' => ['NET', 'NETWORK', 'AP'],
        'wifi' => ['NET', 'NETWORK'],
        
        // Storage & USB
        'flashdisk' => ['STR', 'STORAGE', 'USB'],
        'flash disk' => ['STR', 'STORAGE', 'USB'],
        'usb' => ['STR', 'STORAGE', 'USB'],
        'harddisk' => ['STR', 'STORAGE', 'HDD'],
        'hard disk' => ['STR', 'STORAGE', 'HDD'],
        'ssd' => ['STR', 'STORAGE', 'SSD'],
        'external' => ['STR', 'STORAGE'],
        
        // Charger & Adapter
        'charger' => ['ADP', 'ADAPTER', 'CHARGER'],
        'adapter' => ['ADP', 'ADAPTER'],
        'power supply' => ['ADP', 'ADAPTER', 'PSU'],
        'psu' => ['ADP', 'ADAPTER', 'PSU'],
        
        // Kabel & Connector
        'cable' => ['CBL', 'CABLE', 'KABEL'],
        'kabel' => ['CBL', 'CABLE', 'KABEL'],
        'hdmi' => ['CBL', 'CABLE', 'KABEL'],
        'vga' => ['CBL', 'CABLE', 'KABEL'],
        
        // Projector
        'projector' => ['PRJ', 'PROJECTOR'],
        'proyektor' => ['PRJ', 'PROJECTOR'],
        
        // Webcam & Camera
        'webcam' => ['CAM', 'CAMERA', 'WEBCAM'],
        'camera' => ['CAM', 'CAMERA'],
        'kamera' => ['CAM', 'CAMERA'],
        
        // Server & Rack
        'server' => ['SRV', 'SERVER'],
        'rack' => ['RCK', 'RACK'],
        
        // Meja & Furniture
        'meja' => ['FRN', 'FURNITURE', 'MEJA'],
        'kursi' => ['FRN', 'FURNITURE', 'KURSI'],
        'table' => ['FRN', 'FURNITURE', 'MEJA'],
        'chair' => ['FRN', 'FURNITURE', 'KURSI'],
        'lemari' => ['FRN', 'FURNITURE', 'LEMARI'],
        
        // AC & Cooling
        'ac' => ['AC', 'COOLING'],
        'air conditioner' => ['AC', 'COOLING'],
        'fan' => ['FAN', 'COOLING'],
        'kipas' => ['FAN', 'COOLING'],
        
        // UPS
        'ups' => ['UPS'],
        
        // Telepon
        'phone' => ['PHN', 'PHONE', 'TELEPON'],
        'telephone' => ['PHN', 'PHONE', 'TELEPON'],
        'telepon' => ['PHN', 'PHONE', 'TELEPON'],
    ];

    /**
     * Detect category from asset name
     * Falls back to "Perangkat Lainnya" if no keyword matches
     */
    public function detectCategory(string $assetName): ?Category
    {
        $assetNameLower = Str::lower($assetName);
        
        // Try to match keywords
        foreach ($this->categoryKeywords as $keyword => $possiblePrefixes) {
            if (Str::contains($assetNameLower, $keyword)) {
                // Try to find category by these prefixes
                foreach ($possiblePrefixes as $prefix) {
                    $category = Category::where('prefix', $prefix)
                        ->orWhere('name', 'LIKE', "%{$prefix}%")
                        ->active()
                        ->first();
                    
                    if ($category) {
                        return $category;
                    }
                }
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

    /**
     * Get suggested category name from asset name (for creating new category)
     */
    public function suggestCategoryName(string $assetName): ?string
    {
        $assetNameLower = Str::lower($assetName);
        
        foreach ($this->categoryKeywords as $keyword => $possiblePrefixes) {
            if (Str::contains($assetNameLower, $keyword)) {
                return strtoupper($keyword);
            }
        }
        
        return null;
    }

    /**
     * Get all keyword mappings for reference
     */
    public function getKeywordMappings(): array
    {
        return $this->categoryKeywords;
    }
}
