<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configurations;

class ConfigurationController extends Controller
{
    public function index($slug = null)
    {
        if ($slug) {
            $config = Configurations::where('status', true)->where('slug', $slug)->first();

            if (!$config) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Configuration not found.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data'   => [
                    'slug' => $config->slug,
                    'name' => $config->getTranslations('name'),
                    'note' => $config->note,
                    'type' => $config->inputType,
                ],
            ], 200);
        } else {
            $configs = Configurations::where('status', true)->get();

            return response()->json([
                'status' => true,
                'data'   => $configs->map(fn($config) => [
                    'slug' => $config->slug,
                    'name' => $config->getTranslations('name'),
                    'note' => $config->note,
                    'type' => $config->inputType,
                ]),
            ], 200);
        }
    }
}
