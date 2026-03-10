<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
  public static function getUrl($path)
  {
    if (!$path) {
      return null;
    }

    return Storage::disk('supabase')->url($path);
  }

  public static function getMultipleUrls(array $paths)
  {
    return array_map(function ($path) {
      return self::getUrl($path);
    }, $paths);
  }
}
