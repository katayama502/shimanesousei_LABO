<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Message;
use App\Models\Organization;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\ProjectUpdate;
use App\Models\Sponsorship;
use App\Models\SponsorshipTier;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $sportCategories = collect([
            ['name' => 'サッカー', 'slug' => 'soccer'],
            ['name' => 'バスケットボール', 'slug' => 'basketball'],
            ['name' => '野球', 'slug' => 'baseball'],
        ])->map(fn ($data) => Category::create(array_merge($data, ['type' => 'sport'])));

        $cultureCategories = collect([
            ['name' => '吹奏楽', 'slug' => 'brass-band'],
            ['name' => '合唱', 'slug' => 'chorus'],
            ['name' => '美術', 'slug' => 'art'],
        ])->map(fn ($data) => Category::create(array_merge($data, ['type' => 'culture'])));

        $tags = collect([
            '地域交流', '環境保全', '子ども支援', '国際交流', '伝統文化', 'テクノロジー',
        ])->map(function ($name) {
            return Tag::create([
                'name' => $name,
                'slug' => Str::slug($name) . '-' . Str::random(4),
            ]);
        });

        $clubOrganizations = collect(range(1, 3))->map(function ($i) {
            return Organization::create([
                'name' => "クラブ組織{$i}",
                'type' => 'club',
                'description' => '地域に根ざした部活動組織です。',
                'prefecture' => '東京都',
                'city' => '千代田区',
                'is_verified' => true,
            ]);
        });

        $companyOrganizations = collect(range(1, 3))->map(function ($i) {
            return Organization::create([
                'name' => "企業組織{$i}",
                'type' => 'company',
                'description' => '地域の部活動を応援する企業です。',
                'prefecture' => '大阪府',
                'city' => '大阪市',
                'is_verified' => true,
            ]);
        });

        $clubUsers = collect(range(1, 3))->map(function ($i) use ($clubOrganizations) {
            $user = User::factory()->create([
                'name' => "クラブ担当者{$i}",
                'email' => "club{$i}@example.com",
                'role' => 'club',
                'email_verified_at' => now(),
            ]);

            $organization = $clubOrganizations[$i - 1];
            $organization->users()->attach($user->id, ['role' => 'owner']);

            return $user;
        });

        $companyUsers = collect(range(1, 3))->map(function ($i) use ($companyOrganizations) {
            $user = User::factory()->create([
                'name' => "企業担当者{$i}",
                'email' => "company{$i}@example.com",
                'role' => 'company',
                'email_verified_at' => now(),
            ]);

            $organization = $companyOrganizations[$i - 1];
            $organization->users()->attach($user->id, ['role' => 'owner']);

            return $user;
        });

        $clubOrganizations->each(function (Organization $organization, int $index) use ($sportCategories, $cultureCategories, $tags) {
            foreach (range(1, 2) as $projectIndex) {
                $title = "プロジェクト{$index}{$projectIndex}";
                $project = Project::create([
                    'organization_id' => $organization->id,
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . Str::random(6),
                    'summary' => '地域の子どもたちに向けた活動資金を募っています。',
                    'description' => '活動内容の詳細説明。',
                    'sport_category_id' => $sportCategories->random()->id,
                    'culture_category_id' => $cultureCategories->random()->id,
                    'target_amount' => 500000,
                    'current_amount' => random_int(100000, 300000),
                    'start_at' => Carbon::now()->subWeeks(2),
                    'end_at' => Carbon::now()->addWeeks(4),
                    'status' => 'published',
                    'prefecture' => $organization->prefecture,
                    'city' => $organization->city,
                ]);

                $project->tags()->sync($tags->random(random_int(2, 3))->pluck('id'));

                foreach (range(1, 3) as $tierIndex) {
                    SponsorshipTier::create([
                        'project_id' => $project->id,
                        'name' => "プラン{$tierIndex}",
                        'amount' => $tierIndex * 10000,
                        'description' => 'ロゴ掲載などのリターンが含まれます。',
                        'limit_qty' => $tierIndex === 3 ? null : 10 * $tierIndex,
                    ]);
                }

                foreach (range(1, 3) as $mediaIndex) {
                    ProjectMedia::create([
                        'project_id' => $project->id,
                        'type' => 'image',
                        'path' => "project-media/sample{$mediaIndex}.jpg",
                        'caption' => "活動の様子{$mediaIndex}",
                        'sort' => $mediaIndex,
                    ]);
                }

                foreach (range(1, 2) as $updateIndex) {
                    ProjectUpdate::create([
                        'project_id' => $project->id,
                        'title' => "活動報告{$updateIndex}",
                        'body' => '活動の進捗をお知らせします。',
                        'published_at' => Carbon::now()->subDays($updateIndex * 3),
                    ]);
                }
            }
        });

        $projects = Project::all();

        $companyOrganizations->each(function (Organization $organization) use ($projects, $companyUsers) {
            $project = $projects->random();
            $sponsorship = Sponsorship::create([
                'project_id' => $project->id,
                'company_org_id' => $organization->id,
                'tier_id' => $project->tiers()->inRandomOrder()->first()->id,
                'amount' => random_int(5000, 30000),
                'message' => '活動を応援しています！',
                'status' => Arr::random(['pending', 'approved']),
                'payment_method' => Arr::random(['invoice', 'bank', 'offline']),
            ]);

            $messagesCount = random_int(1, 3);
            for ($i = 0; $i < $messagesCount; $i++) {
                Message::create([
                    'sponsorship_id' => $sponsorship->id,
                    'sender_user_id' => Arr::random([$companyUsers->random()->id, $project->organization->users()->first()->id]),
                    'body' => "メッセージ{$i}です。よろしくお願いします。",
                    'read_at' => $i % 2 === 0 ? now() : null,
                ]);
            }
        });

        $projects->take(2)->each(function (Project $project) {
            \App\Models\Report::create([
                'reportable_type' => Project::class,
                'reportable_id' => $project->id,
                'reporter_user_id' => User::first()->id,
                'reason' => '不適切な内容が含まれている可能性があります。',
            ]);
        });
    }
}
