<?php

namespace Tests\Feature;

use App\Models\PageType;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplatesApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private PageType $pageType1;
    private PageType $pageType2;
    private Template $template1;
    private Template $template2;
    private Template $template3;
    private Template $inactiveTemplate;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication tests
        $this->user = User::factory()->create();

        // Create mock PageTypes
        $this->pageType1 = PageType::create([
            'name' => ['en' => 'Profile', 'ar' => 'ملف شخصي'],
            'slug' => 'profile',
            'description' => ['en' => 'Profile page type', 'ar' => 'نوع صفحة الملف الشخصي'],
        ]);

        $this->pageType2 = PageType::create([
            'name' => ['en' => 'Restaurant', 'ar' => 'مطعم'],
            'slug' => 'restaurant',
            'description' => ['en' => 'Restaurant page type', 'ar' => 'نوع صفحة المطعم'],
        ]);

        // Create templates for PageType 1
        $this->template1 = Template::create([
            'name' => ['en' => 'Template 1', 'ar' => 'قالب 1'],
            'slug' => 'template-1',
            'description' => ['en' => 'Description 1', 'ar' => 'وصف 1'],
            'preview_image' => 'templates/temp1.png',
            'page_type_id' => $this->pageType1->id,
            'status' => 1,
        ]);

        $this->template2 = Template::create([
            'name' => ['en' => 'Template 2', 'ar' => 'قالب 2'],
            'slug' => 'template-2',
            'description' => ['en' => 'Description 2', 'ar' => 'وصف 2'],
            'preview_image' => 'images/templates/cache/temp2.png',
            'page_type_id' => $this->pageType1->id,
            'status' => 1,
        ]);

        // Inactive template for PageType 1
        $this->inactiveTemplate = Template::create([
            'name' => ['en' => 'Template Inactive', 'ar' => 'قالب غير نشط'],
            'slug' => 'template-inactive',
            'description' => ['en' => 'Description Inactive', 'ar' => 'وصف غير نشط'],
            'preview_image' => 'templates/inactive.png',
            'page_type_id' => $this->pageType1->id,
            'status' => 0,
        ]);

        // Create template for PageType 2
        $this->template3 = Template::create([
            'name' => ['en' => 'Template 3', 'ar' => 'قالب 3'],
            'slug' => 'template-3',
            'description' => ['en' => 'Description 3', 'ar' => 'وصف 3'],
            'preview_image' => 'templates/temp3.png',
            'page_type_id' => $this->pageType2->id,
            'status' => 1,
        ]);
    }

    /**
     * Test index requires authentication.
     */
    public function test_get_all_templates_requires_authentication(): void
    {
        $response = $this->getJson(route('api.templates.index'));
        $response->assertStatus(401);
    }

    /**
     * Test filter by page_type_id requires authentication.
     */
    public function test_get_templates_by_page_type_route_requires_authentication(): void
    {
        $response = $this->getJson(route('api.templates.byPageType', ['page_type_id' => $this->pageType2->id]));
        $response->assertStatus(401);
    }

    /**
     * Test get all templates when authenticated.
     */
    public function test_get_all_templates_authenticated(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.templates.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'page_type_id',
                        'name',
                        'slug',
                        'description',
                        'name_translations',
                        'description_translations',
                        'preview_image',
                        'preview_image_url',
                        'thumbnail_url',
                        'status',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);

        // Should return only 3 active templates (template1, template2, template3)
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Test filtering templates by page_type_id via query parameter when authenticated.
     */
    public function test_filter_templates_by_page_type_query_param_authenticated(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.templates.index', ['page_type_id' => $this->pageType1->id]));

        $response->assertStatus(200);

        // Should return only template1 and template2 (active templates for pageType1)
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['slug' => 'template-1']);
        $response->assertJsonFragment(['slug' => 'template-2']);
        $response->assertJsonMissing(['slug' => 'template-3']);
        $response->assertJsonMissing(['slug' => 'template-inactive']);
    }

    /**
     * Test getting templates by page type via route parameter when authenticated.
     */
    public function test_get_templates_by_page_type_route_authenticated(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.templates.byPageType', ['page_type_id' => $this->pageType2->id]));

        $response->assertStatus(200);

        // Should return only template3
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['slug' => 'template-3']);
        $response->assertJsonMissing(['slug' => 'template-1']);
        $response->assertJsonMissing(['slug' => 'template-2']);
    }

    /**
     * Test verification of absolute image URLs.
     */
    public function test_template_image_urls(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson(route('api.templates.index'));

        $response->assertStatus(200);
        $data = $response->json('data');

        foreach ($data as $template) {
            if ($template['slug'] === 'template-2') {
                // Should use the standard storage cache path for preview and thumbnail path for thumbnail
                $this->assertStringContainsString('storage/images/templates/cache/temp2.png', $template['preview_image_url']);
                $this->assertStringContainsString('storage/images/templates/thumbnail/temp2.png', $template['thumbnail_url']);
            } elseif ($template['slug'] === 'template-1') {
                // Seeded format templates/temp1.png
                $this->assertStringContainsString('storage/templates/temp1.png', $template['preview_image_url']);
                $this->assertStringContainsString('storage/templates/temp1.png', $template['thumbnail_url']);
            }
        }
    }

    /**
     * Test retrieving a single template publicly.
     */
    public function test_show_template_publicly(): void
    {
        $response = $this->getJson(route('api.templates.show', ['id' => $this->template1->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'page_type_id',
                    'name',
                    'slug',
                    'description',
                    'name_translations',
                    'description_translations',
                    'preview_image',
                    'preview_image_url',
                    'thumbnail_url',
                    'status',
                    'created_at',
                    'updated_at',
                ]
            ]);

        $this->assertEquals($this->template1->id, $response->json('data.id'));
        $this->assertEquals('template-1', $response->json('data.slug'));
    }

    /**
     * Test retrieving a single template publicly returns 404 for inactive templates.
     */
    public function test_show_template_publicly_returns_404_for_inactive(): void
    {
        $response = $this->getJson(route('api.templates.show', ['id' => $this->inactiveTemplate->id]));
        $response->assertStatus(404);
    }

    /**
     * Test retrieving a single template publicly returns 404 for non-existent templates.
     */
    public function test_show_template_publicly_returns_404_for_non_existent(): void
    {
        $response = $this->getJson(route('api.templates.show', ['id' => 99999]));
        $response->assertStatus(404);
    }
}
