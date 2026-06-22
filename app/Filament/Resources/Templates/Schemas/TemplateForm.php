<?php

namespace App\Filament\Resources\Templates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\Storage;

class TemplateForm
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
                                    ->required()
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
                                    ->required()
                                    ->afterStateHydrated(fn($state, $record, $component) => $component->state($record?->getTranslation('description', 'ar'))),
                            ]),
                    ])
                    ->columnSpanFull(),


                TextInput::make('slug')
                    ->required(),
                FileUpload::make('preview_image')
                    ->disk('public')
                    ->directory('images/templates/cache')
                    ->visibility('public')
                    ->image()
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $record, $get): ?string {
                        $slug = $get('slug') ? \Illuminate\Support\Str::slug($get('slug')) : 'template';
                        $id = $record ? $record->id : ((\App\Models\Template::max('id') ?? 0) + 1);
                        $dateTime = date('Ymd_His');
                        $extension = $file->getClientOriginalExtension();
                        $filename = "{$slug}_{$id}_{$dateTime}.{$extension}";
                        
                        // Extract filename from old record if exists, and delete old files
                        if ($record && $record->preview_image) {
                            $oldFilename = basename($record->preview_image);
                            
                            // Delete from cache
                            Storage::disk('public')->delete("images/templates/cache/{$oldFilename}");
                            
                            // Delete from thumbnail
                            Storage::disk('public')->delete("images/templates/thumbnail/{$oldFilename}");
                        }
                        
                        $mainPath = 'images/templates/cache/' . $filename;
                        $tempPath = $file->getRealPath();
                        
                        self::resizeAndSaveImage($tempPath, $filename);
                        
                        return $mainPath;
                    }),
                Select::make('page_type_id')
                    ->label('Type')
                    ->relationship('pageType', 'name')
                    ->required(),
                Toggle::make('status')
                    ->required(),
            ]);
    }

    protected static function resizeAndSaveImage(string $tempPath, string $filename): void
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
        
        $sizes = [
            'main' => [
                'width' => 414,
                'height' => 896,
                'paths' => [
                    'images/templates/cache/' . $filename,
                ]
            ],
            'thumbnail' => [
                'width' => 92,
                'height' => 200,
                'paths' => [
                    'images/templates/thumbnail/' . $filename,
                ]
            ]
        ];
        
        foreach ($sizes as $config) {
            $targetWidth = $config['width'];
            $targetHeight = $config['height'];
            
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
                0, 0, 0, 0,
                $targetWidth, $targetHeight,
                $origWidth, $origHeight
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
            
            foreach ($config['paths'] as $path) {
                $directory = dirname($path);
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                Storage::disk('public')->put($path, $imageData, 'public');
            }
        }
        
        imagedestroy($source);
    }
}

