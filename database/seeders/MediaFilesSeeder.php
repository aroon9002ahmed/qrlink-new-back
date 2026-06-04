<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class MediaFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //pageTypes
        if (!File::exists(storage_path('app/public/images/pageTypes'))) {
            File::makeDirectory(storage_path('app/public/images/pageTypes'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/pageTypes/cache'), 0775, true);
        }

        //templates
        if (!File::exists(storage_path('app/public/images/templates'))) {
            File::makeDirectory(storage_path('app/public/images/templates'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/templates/cache'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/templates/small'), 0775, true);
        }

        //brands
        if (!File::exists(storage_path('app/public/images/brands'))) {
            File::makeDirectory(storage_path('app/public/images/brands'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/brands/cache'), 0775, true);

        }

        //products
        if (!File::exists(storage_path('app/public/images/products'))) {
            File::makeDirectory(storage_path('app/public/images/products'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/products/cache'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/products/small'), 0775, true);
        }

        //products gallery
        if (!File::exists(storage_path('app/public/images/products/gallery'))) {
            File::makeDirectory(storage_path('app/public/images/products/gallery'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/products/gallery/cache'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/products/gallery/small'), 0775, true);
        }


        //Sliders
        if (!File::exists(storage_path('app/public/images/sliders'))) {
            File::makeDirectory(storage_path('app/public/images/sliders'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/sliders/cache'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/sliders/small'), 0775, true);
        }

        //Banners
        if (!File::exists(storage_path('app/public/images/banners'))) {
            File::makeDirectory(storage_path('app/public/images/banners'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/banners/cache'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/banners/small'), 0775, true);
        }
        //Home Products
        if (!File::exists(storage_path('app/public/images/homeProducts'))) {
            File::makeDirectory(storage_path('app/public/images/homeProducts'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/homeProducts/cache'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/homeProducts/small'), 0775, true);
        }
        //paymentGateway
        if (!File::exists(storage_path('app/public/images/paymentGateway'))) {
            File::makeDirectory(storage_path('app/public/images/paymentGateway'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/paymentGateway/cache'), 0775, true);
            File::makeDirectory(storage_path('app/public/images/paymentGateway/small'), 0775, true);
        }
    }
}
