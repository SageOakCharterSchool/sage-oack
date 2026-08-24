<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use NunoMaduro\Collision\ConsoleColor;

class ConsolidateColor extends Model
{
    use HasFactory;

    protected $perPage = 300;

    protected $table = "consolidate_colors";
    protected $fillable = [
        'cycle_id',
        'column_name',
        'value',
        'background_color',
        'color',
        'created_by',
    ];

    protected function cloneColorsIntoNewCycle($cycleFrom, $cycleTo)
    {
        $this->where("cycle_id", $cycleTo)->delete(); // remove all tables for new cycle
        $colors = $this->where("cycle_id", $cycleFrom)
            ->get();
        foreach ($colors as $row) {
            $newColor = $row->replicate();
            $newColor->cycle_id = $cycleTo;
            $newColor->save();
        }
    }

    protected function getAllColumnColors($cycleId): array {
        $consolidateColors = $this->where("cycle_id", $cycleId)
            ->get();
        $colors = [];
        foreach ($consolidateColors as $consolidateColor) {
            $colors[$consolidateColor->column_name][$consolidateColor->value]['background_color'] = $consolidateColor->background_color;
            $colors[$consolidateColor->column_name][$consolidateColor->value]['color'] = $consolidateColor->color;
        }
        return $colors;
    }
}
