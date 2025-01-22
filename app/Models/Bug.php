<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    use HasFactory;

    protected $table = "bugs";

    protected $fillable = [
        'app',
        'feedback',
        'status',
        'resolved_on',
    ];
}
