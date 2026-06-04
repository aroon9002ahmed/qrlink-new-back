<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Block;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing blocks to include border settings
        $blocks = Block::all();

        foreach ($blocks as $block) {
            $settings = $block->settings ?? [];

            // Add border settings if they don't exist
            if (!isset($settings['show_border'])) {
                $settings['show_border'] = false;
            }

            if (!isset($settings['border_color'])) {
                $settings['border_color'] = '#FF0000FF'; // Default gray-200
            }

            $block->update(['settings' => $settings]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove border settings from existing blocks
        $blocks = Block::all();

        foreach ($blocks as $block) {
            $settings = $block->settings ?? [];

            // Remove border settings
            unset($settings['show_border']);
            unset($settings['border_color']);

            $block->update(['settings' => $settings]);
        }
    }
};
