<?php

namespace App\Filament\Resources\PageTypes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

class PageTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (EN)')
                                    ->required()
                                    ->live()
                                    ->afterStateHydrated(fn($state, $record, $component) => $component->state($record?->getTranslation('name', 'en')))
                                    ->afterStateUpdated(function ($state, $set, $record) {
                                        if (!$record) {
                                            $set('slug', \Illuminate\Support\Str::slug($state));
                                        }
                                    }),
                                TextInput::make('description.en')
                                    ->label('Description (EN)')
                                    ->afterStateHydrated(fn($state, $record, $component) => $component->state($record?->getTranslation('description', 'en'))),
                            ]),
                        Tab::make('Arabic')
                            ->schema([
                                TextInput::make('name.ar')
                                    ->label('Name (AR)')
                                    ->required()
                                    ->afterStateHydrated(fn($state, $record, $component) => $component->state($record?->getTranslation('name', 'ar'))),
                                TextInput::make('description.ar')
                                    ->label('Description (AR)')
                                    ->afterStateHydrated(fn($state, $record, $component) => $component->state($record?->getTranslation('description', 'ar'))),
                            ]),
                    ])
                    ->columnSpanFull(),

                TextInput::make('slug')
                    ->required()
                    ->unique(table: 'page_types', column: 'slug', ignoreRecord: true),
                FileUpload::make('icon')
                    ->disk('public')
                    ->directory('images/pageTypes/cache')
                    ->visibility('public')
                    ->image()
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $record, $get): ?string {
                        $slug = $get('slug') ? \Illuminate\Support\Str::slug($get('slug')) : 'page_type';
                        $dateTime = date('Ymd_His');
                        $extension = strtolower($file->getClientOriginalExtension());
                        $filename = "{$slug}_{$dateTime}.{$extension}";

                        if ($record && $record->icon) {
                            $oldPath = str_starts_with($record->icon, 'images/pageTypes/cache/')
                                ? $record->icon
                                : "images/pageTypes/cache/{$record->icon}";
                            Storage::disk('public')->delete($oldPath);
                        }

                        $tempPath = $file->getRealPath();

                        if ($extension === 'svg') {
                            Storage::disk('public')->putFileAs('images/pageTypes/cache', $file, $filename, 'public');
                        } else {
                            self::resizeAndSaveIcon($tempPath, $filename);
                        }

                        return "images/pageTypes/cache/{$filename}";
                    })
                    ->deleteUploadedFileUsing(function ($file, $record) {
                        if ($file) {
                            $path = str_starts_with($file, 'images/pageTypes/cache/')
                                ? $file
                                : "images/pageTypes/cache/{$file}";
                            Storage::disk('public')->delete($path);
                        }
                    }),
                Toggle::make('status')
                    ->required(),
                \Filament\Schemas\Components\Section::make('Active Services & Features')
                    ->description('Determine which features are enabled for this page type.')
                    ->schema([
                        Toggle::make('has_banners')->label('Banners'),
                        Toggle::make('has_social_media')->label('Social Media'),
                        Toggle::make('has_branches')->label('Branches'),
                        Toggle::make('has_products')->label('Products'),
                        Toggle::make('has_orders')->label('Orders'),
                        Toggle::make('has_tables')->label('Tables (Dine-In)'),
                    ])
                    ->columns(3),
            ]);
    }

    protected static function resizeAndSaveIcon(string $tempPath, string $filename): void
    {
        $imageInfo = @getimagesize($tempPath);
        if (!$imageInfo) {
            return;
        }

        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $source = @imagecreatefromjpeg($tempPath);
                break;
            case 'image/png':
                $source = @imagecreatefrompng($tempPath);
                break;
            case 'image/webp':
                $source = @imagecreatefromwebp($tempPath);
                break;
            case 'image/gif':
                $source = @imagecreatefromgif($tempPath);
                break;
            default:
                $source = null;
                break;
        }

        if (!$source) {
            return;
        }

        $targetWidth = 48;
        $targetHeight = 48;

        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
            $transparent = imagecolorallocatealpha($targetImage, 255, 255, 255, 127);
            imagefilledrectangle($targetImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        $origWidth = imagesx($source);
        $origHeight = imagesy($source);

        imagecopyresampled(
            $targetImage,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        ob_start();
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($targetImage, null, 90);
                break;
            case 'image/png':
                imagepng($targetImage, null, 6);
                break;
            case 'image/webp':
                imagewebp($targetImage, null, 85);
                break;
            case 'image/gif':
                imagegif($targetImage);
                break;
        }
        $imageData = ob_get_clean();
        imagedestroy($targetImage);
        imagedestroy($source);

        $path = 'images/pageTypes/cache/' . $filename;
        $directory = dirname($path);
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        Storage::disk('public')->put($path, $imageData, 'public');
    }
}
