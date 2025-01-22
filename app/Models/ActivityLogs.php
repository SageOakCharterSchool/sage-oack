<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogs extends Model
{
    use HasFactory;
    protected $table = "activity_logs";
    protected  $fillable = [
        'created_by', //created_by
        'action', // what was done
    ];
}
