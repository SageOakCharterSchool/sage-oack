<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Resource extends Model
{
    use HasFactory;

    protected $perPage = 20;

    protected $fillable = ['resource', 'resource_url', 'description',  'resource_thumbnail','status', 'created_by'];

    protected function getImageByPath($path) {
        $imageContent = Storage::disk('s3')->get($path);
        //$type = pathinfo($path, PATHINFO_EXTENSION);
        $base64 = 'data:image/jpeg;base64,' . base64_encode( $imageContent );
        return $base64;
    }
}
