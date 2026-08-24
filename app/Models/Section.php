<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $perPage = 20;

    protected $table = "sections";

    protected $fillable = [
        'section',
        'color',
        'font_color',
        'created_by'
    ];

    protected function getSectionInfo($sectionId)
    {
        return $this->where('id', $sectionId)
            ->first();
    }

    protected function getSectionsToSelect()
    {
        return $this->orderBy('section')->pluck('section', 'id');
    }

    protected function getSectionsKeyedById()
    {
        return Section::orderBy('section')->get()->keyBy('id');
    }
}
