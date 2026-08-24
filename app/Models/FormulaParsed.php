<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulaParsed extends Model
{
    use HasFactory;

    protected $table = "formula_parse";

    protected $fillable = [
        'cycle_id',
        'formula_id',
        'student_id',
        'formula_result',
        'created_by',
    ];
}
