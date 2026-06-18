<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function it_rejects_non_image_file_uploads()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('malicious.php', 100, 'application/php');

        $response = $this->actingAs($this->admin)->post(route('admin.assets.store'), [
            'nama_asset' => 'Test Asset',
            'category_id' => $category->id,
            'kondisi' => 'baik',
            'status' => 'available',
            'foto_asset' => $file,
        ]);

        $response->assertSessionHasErrors('foto_asset');
    }

    /** @test */
    public function it_rejects_oversized_image_uploads()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        // Create a 3MB file (exceeds 2MB limit)
        $file = UploadedFile::fake()->image('large.jpg')->size(3072);

        $response = $this->actingAs($this->admin)->post(route('admin.assets.store'), [
            'nama_asset' => 'Test Asset',
            'category_id' => $category->id,
            'kondisi' => 'baik',
            'status' => 'available',
            'foto_asset' => $file,
        ]);

        $response->assertSessionHasErrors('foto_asset');
    }

    /** @test */
    public function it_accepts_valid_image_uploads()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $file = UploadedFile::fake()->image('photo.jpg')->size(1024); // 1MB

        $response = $this->actingAs($this->admin)->post(route('admin.assets.store'), [
            'nama_asset' => 'Test Asset',
            'category_id' => $category->id,
            'kondisi' => 'baik',
            'status' => 'available',
            'foto_asset' => $file,
        ]);

        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function it_validates_phone_number_format()
    {
        $asset = Asset::factory()->create(['status' => 'available']);

        // Invalid formats
        $invalidPhones = [
            '123',
            'abcdefghij',
            '08123',
            '+1234567890',
            '1234567890123456',
        ];

        foreach ($invalidPhones as $phone) {
            $response = $this->post(route('scan.borrow'), [
                'asset_id' => $asset->id,
                'borrower_name' => 'Test User',
                'borrower_email' => 'test@example.com',
                'borrower_phone' => $phone,
                'purpose' => 'Testing',
                'borrow_date' => now()->addDay()->format('Y-m-d'),
            ]);

            $response->assertSessionHasErrors('borrower_phone');
        }

        // Valid formats
        $validPhones = [
            '081234567890',
            '628123456789',
            '+6281234567890',
            '08123456789',
        ];

        foreach ($validPhones as $phone) {
            $response = $this->post(route('scan.borrow'), [
                'asset_id' => $asset->id,
                'borrower_name' => 'Test User',
                'borrower_email' => 'test@example.com',
                'borrower_phone' => $phone,
                'purpose' => 'Testing',
                'borrow_date' => now()->addDay()->format('Y-m-d'),
            ]);

            $response->assertSessionHasNoErrors('borrower_phone');
        }
    }

    /** @test */
    public function it_validates_email_format()
    {
        $asset = Asset::factory()->create(['status' => 'available']);

        // Invalid emails
        $response = $this->post(route('scan.borrow'), [
            'asset_id' => $asset->id,
            'borrower_name' => 'Test User',
            'borrower_email' => 'not-an-email',
            'borrower_phone' => '081234567890',
            'purpose' => 'Testing',
            'borrow_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('borrower_email');
    }

    /** @test */
    public function public_scan_endpoint_is_rate_limited()
    {
        $asset = Asset::factory()->create();

        // Make 31 requests (rate limit is 30/minute)
        for ($i = 0; $i < 31; $i++) {
            $response = $this->get(route('scan.show', $asset->kode_asset));

            if ($i < 30) {
                $response->assertOk();
            } else {
                // 31st request should be rate limited
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }

    /** @test */
    public function borrowing_request_endpoint_is_rate_limited()
    {
        $asset = Asset::factory()->create(['status' => 'available']);

        // Make 6 requests (rate limit is 5/minute)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post(route('scan.borrow'), [
                'asset_id' => $asset->id,
                'borrower_name' => 'Test User',
                'borrower_email' => "test{$i}@example.com",
                'borrower_phone' => '081234567890',
                'purpose' => 'Testing',
                'borrow_date' => now()->addDay()->format('Y-m-d'),
            ]);

            if ($i < 5) {
                // First 5 should succeed (may redirect)
                $this->assertContains($response->status(), [200, 302]);
            } else {
                // 6th request should be rate limited
                $response->assertStatus(429);
            }
        }
    }
}
