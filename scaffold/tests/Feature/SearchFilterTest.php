<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_by_tag(): void
    {
        $project = Project::factory()->create(['status' => 'published']);
        $tag = Tag::factory()->create();
        $project->tags()->attach($tag->id);

        $response = $this->get(route('projects.search', ['tag' => $tag->slug]));

        $response->assertOk();
        $response->assertSee($project->title);
    }

    public function test_can_filter_by_category(): void
    {
        $category = Category::factory()->create(['type' => 'sport']);
        $project = Project::factory()->create(['sport_category_id' => $category->id]);

        $response = $this->get(route('projects.search', [
            'category' => $category->slug,
            'type' => 'sport',
        ]));

        $response->assertOk();
        $response->assertSee($project->title);
    }
}
