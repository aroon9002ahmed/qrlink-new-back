<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\RestaurantBranch;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(AdminTableSeeder::class);
        // $this->call(PermissionTableSeeder::class);
        // $this->call(RoleTableSeeder::class);
        $this->call(ConfigrationTableSeeder::class);
        $this->call(FAQTableSeeder::class);
        $this->call(MenuLocationTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(MediaFilesSeeder::class);
        $this->call(SubscriptionPlanSeeder::class);
        $this->call(TranslateContributorsTable::class); //add admin user to ltu_contributors table
        $this->call(UserTableSeeder::class);
        $this->call(UserSubscriptionsSeeder::class);
        $this->call(MainPageSeeder::class);
        $this->call(PageTypeTableseeder::class);
        $this->call(TemplatesTableseeder::class);
        $this->call(PageTableSeeder::class);
        $this->call(SocialPlatformsSeeder::class);
        $this->call(SocialLinksSeeder::class);
        $this->call(BlockTypeSeeder::class);
        $this->call(RestaurantSettingsSeeder::class);
        $this->call(RestaurantBranchesSeeder::class);
        $this->call(RestaurantMenuSeeder::class);
        $this->call(RestaurantTablesSeeder::class);
        $this->call(RestaurantOrdersSeeder::class);
        $this->call(LinkTableSeeder::class);
        $this->call(QrcodeSeeder::class);
    }
}
