<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'genre',
        'poster',
        'poster_name',
        'poster_uuid',
        'release_date',
    ];

    /* =====================
       🎬 File Handling Logic
       ===================== */

    public static function uploadPoster(?UploadedFile $file): ?array
    {
        if (!$file) return null;

        $uuid = Str::uuid()->toString();
        $folder = "movies/" . now()->format("Y/m/");

        // ✅ ensure folder exists with proper permissions
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder, 0755, true);
        }

        $posterName = $uuid . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $posterName, 'public');

        return [
            'poster' => self::posterUrl($path),
            'poster_name' => $posterName,
            'poster_uuid' => $uuid,
        ];
    }

    public static function posterUrl(string $path): string
    {
        $baseUrl = config('constants.asset_url', config('app.url') . '/storage');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    public function deletePoster(): void
    {
        if (!$this->poster_name) return;

        $folderPattern = "movies/*/";
        $allFiles = Storage::disk('public')->allFiles('movies');

        foreach ($allFiles as $file) {
            if (Str::endsWith($file, $this->poster_name) && Storage::disk('public')->exists($file)) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
