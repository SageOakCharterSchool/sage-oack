<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cycle extends Model
{
    use HasFactory;

    protected $table = "cycles";

    protected $fillable = [
        'date_from',
        'date_to',
        'cycle_name',
        'cycle_name',
    ];

    public static function getTableName()
    {
        return (new self())->getTable();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'created_by');
    }

    protected function getCurrentCycle() {
        return $this->whereRaw("'" . date("Y-m-d") . "' BETWEEN date_from and date_to")
                    ->first();
    }

    protected function getAllCycles() {
        return $this->orderBy('date_from','asc')->get();
    }
}
