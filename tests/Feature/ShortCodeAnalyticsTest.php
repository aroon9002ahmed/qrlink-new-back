<?php

namespace Tests\Feature;

use App\Models\ShortCode;
use App\Models\ShortCodeAnalytic;
use App\Models\User;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;
use Tests\TestCase;

class ShortCodeAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that scanning tracking works, resolves location (mocked), and increments clicks.
     */
    public function test_scan_can_be_tracked_successfully(): void
    {
        // Mock Location facade response
        $position = new Position();
        $position->countryName = 'United States';
        $position->cityName = 'San Francisco';
        Location::shouldReceive('get')->andReturn($position);

        // Create a user and a short code
        $user = User::factory()->create();
        
        // ShortCode belongs to a codeable (e.g. Link or Qrcode)
        $link = Link::create([
            'user_id' => $user->id,
            'title' => 'Test Link',
            'original_url' => 'https://google.com',
            'url_hash' => hash('sha256', 'https://google.com'),
        ]);

        $shortCode = ShortCode::create([
            'code' => 'testcode',
            'codeable_id' => $link->id,
            'codeable_type' => Link::class,
            'user_id' => $user->id,
            'clicks' => 5,
        ]);

        // Send track request
        $response = $this->postJson(route('api.short-code-analytics.store'), [
            'short_code_id' => $shortCode->id,
        ], [
            'User-Agent' => 'TestAgent',
            'REMOTE_ADDR' => '8.8.8.8',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status' => true,
            'message' => 'Scan tracked successfully.',
        ]);

        // Assert DB records
        $this->assertDatabaseHas('short_code_analytics', [
            'short_code_id' => $shortCode->id,
            'user_agent' => 'TestAgent',
            'country' => 'United States',
            'city' => 'San Francisco',
        ]);

        // Assert click count was incremented
        $this->assertEquals(6, $shortCode->refresh()->clicks);

        // Track scan from the same IP again
        $responseDuplicate = $this->postJson(route('api.short-code-analytics.store'), [
            'short_code_id' => $shortCode->id,
        ], [
            'User-Agent' => 'TestAgent',
            'REMOTE_ADDR' => '8.8.8.8',
        ]);

        $responseDuplicate->assertStatus(201);

        // Click count should remain 6 (no duplicate count)
        $this->assertEquals(6, $shortCode->refresh()->clicks);

        // Count of analytics records should still be 1 (no duplicate record)
        $this->assertEquals(1, ShortCodeAnalytic::where('short_code_id', $shortCode->id)->count());
    }

    /**
     * Test that scanning tracking works via code string instead of ID.
     */
    public function test_scan_can_be_tracked_via_code_string(): void
    {
        // Mock Location facade response
        $position = new Position();
        $position->countryName = 'Canada';
        $position->cityName = 'Toronto';
        Location::shouldReceive('get')->andReturn($position);

        $user = User::factory()->create();
        $link = Link::create([
            'user_id' => $user->id,
            'title' => 'Test Link 2',
            'original_url' => 'https://yahoo.com',
            'url_hash' => hash('sha256', 'https://yahoo.com'),
        ]);

        $shortCode = ShortCode::create([
            'code' => 'yahoocode',
            'codeable_id' => $link->id,
            'codeable_type' => Link::class,
            'user_id' => $user->id,
            'clicks' => 10,
        ]);

        $response = $this->postJson(route('api.short-code-analytics.store'), [
            'code' => 'yahoocode',
        ], [
            'User-Agent' => 'TestAgent2',
            'REMOTE_ADDR' => '8.8.8.8',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('short_code_analytics', [
            'short_code_id' => $shortCode->id,
            'user_agent' => 'TestAgent2',
            'country' => 'Canada',
            'city' => 'Toronto',
        ]);

        $this->assertEquals(11, $shortCode->refresh()->clicks);
    }

    /**
     * Test validation rules on storing tracking data.
     */
    public function test_store_validation(): void
    {
        $response = $this->postJson(route('api.short-code-analytics.store'), []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['short_code_id']);
    }

    /**
     * Test that guest access is denied for viewing analytics.
     */
    public function test_guest_cannot_view_analytics(): void
    {
        $response = $this->getJson(route('api.short-code-analytics.index'));
        $response->assertStatus(401);
    }

    /**
     * Test that an authenticated user can view analytics of their short codes.
     */
    public function test_authenticated_user_can_view_own_analytics(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create short codes
        $link1 = Link::create(['user_id' => $user1->id, 'original_url' => 'https://a.com', 'url_hash' => hash('sha256', 'https://a.com')]);
        $sc1 = ShortCode::create([
            'code' => 'code1',
            'codeable_id' => $link1->id,
            'codeable_type' => Link::class,
            'user_id' => $user1->id,
        ]);

        $link2 = Link::create(['user_id' => $user2->id, 'original_url' => 'https://b.com', 'url_hash' => hash('sha256', 'https://b.com')]);
        $sc2 = ShortCode::create([
            'code' => 'code2',
            'codeable_id' => $link2->id,
            'codeable_type' => Link::class,
            'user_id' => $user2->id,
        ]);

        // Create analytics records
        ShortCodeAnalytic::create([
            'short_code_id' => $sc1->id,
            'ip_address' => '1.1.1.1',
            'user_agent' => 'UA1',
            'country' => 'US',
            'city' => 'LA',
        ]);

        ShortCodeAnalytic::create([
            'short_code_id' => $sc2->id,
            'ip_address' => '2.2.2.2',
            'user_agent' => 'UA2',
            'country' => 'UK',
            'city' => 'London',
        ]);

        // Query analytics as User 1
        $response = $this->actingAs($user1, 'sanctum')
            ->getJson(route('api.short-code-analytics.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.userAgent', 'UA1');
        $response->assertJsonPath('data.0.ipAddress', '1.1.1.1');

        // Query specifically for short code 2 (unowned) as User 1 should return 403
        $responseForbidden = $this->actingAs($user1, 'sanctum')
            ->getJson(route('api.short-code-analytics.index', ['short_code_id' => $sc2->id]));
        $responseForbidden->assertStatus(403);

        // Query by code parameter (owned code1) as User 1 should return 200 with 1 result
        $responseByCode = $this->actingAs($user1, 'sanctum')
            ->getJson(route('api.short-code-analytics.index', ['code' => 'code1']));
        $responseByCode->assertStatus(200);
        $responseByCode->assertJsonCount(1, 'data');

        // Query by code parameter (unowned code2) as User 1 should return 403
        $responseByCodeForbidden = $this->actingAs($user1, 'sanctum')
            ->getJson(route('api.short-code-analytics.index', ['code' => 'code2']));
        $responseByCodeForbidden->assertStatus(403);
    }

    /**
     * Test that user can filter analytics by date range.
     */
    public function test_user_can_filter_analytics_by_date_range(): void
    {
        $user = User::factory()->create();
        $link = Link::create(['user_id' => $user->id, 'original_url' => 'https://a.com', 'url_hash' => hash('sha256', 'https://a.com')]);
        $sc = ShortCode::create([
            'code' => 'code1',
            'codeable_id' => $link->id,
            'codeable_type' => Link::class,
            'user_id' => $user->id,
        ]);

        // Create an analytic record for 5 days ago
        $a1 = new ShortCodeAnalytic([
            'short_code_id' => $sc->id,
            'ip_address' => '1.1.1.1',
            'user_agent' => 'UA1',
        ]);
        $a1->created_at = now()->subDays(5);
        $a1->save();

        // Create an analytic record for yesterday
        $a2 = new ShortCodeAnalytic([
            'short_code_id' => $sc->id,
            'ip_address' => '2.2.2.2',
            'user_agent' => 'UA2',
        ]);
        $a2->created_at = now()->subDays(1);
        $a2->save();

        // Create an analytic record for today
        $a3 = new ShortCodeAnalytic([
            'short_code_id' => $sc->id,
            'ip_address' => '3.3.3.3',
            'user_agent' => 'UA3',
        ]);
        $a3->created_at = now();
        $a3->save();

        // 1. Fetch today's analytics (start_date and end_date = today)
        $responseToday = $this->actingAs($user, 'sanctum')
            ->getJson(route('api.short-code-analytics.index', [
                'code' => 'code1',
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
            ]));
        $responseToday->assertStatus(200);
        $responseToday->assertJsonCount(1, 'data');
        $responseToday->assertJsonPath('data.0.ipAddress', '3.3.3.3');

        // 2. Fetch yesterday's analytics (start_date and end_date = yesterday)
        $responseYesterday = $this->actingAs($user, 'sanctum')
            ->getJson(route('api.short-code-analytics.index', [
                'code' => 'code1',
                'start_date' => now()->subDays(1)->toDateString(),
                'end_date' => now()->subDays(1)->toDateString(),
            ]));
        $responseYesterday->assertStatus(200);
        $responseYesterday->assertJsonCount(1, 'data');
        $responseYesterday->assertJsonPath('data.0.ipAddress', '2.2.2.2');

        // 3. Fetch from 3 days ago until today
        $responseRange = $this->actingAs($user, 'sanctum')
            ->getJson(route('api.short-code-analytics.index', [
                'code' => 'code1',
                'start_date' => now()->subDays(3)->toDateString(),
            ]));
        $responseRange->assertStatus(200);
        $responseRange->assertJsonCount(2, 'data'); // yesterday and today
    }
}
