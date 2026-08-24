<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableAlias extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'table_aliases';
    protected $perPage = 200;
    protected $fillable = [
        'table_name',
        'table_alias',
        'created_by'
    ];

    protected function getTableAlias() {
        return $this->orderBy('id')->get();
    }
}
