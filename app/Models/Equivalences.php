<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equivalences extends Model
{
    use HasFactory;
    protected $perPage = 200;

    protected $table = "equivalences";
    protected $fillable = [
        'equivalence',
        'value',
        'color',
        'created_by',
    ];

    protected function getEquivalenceColor($formulaId,$cycle,$consolidateColumn) {
        if (!$consolidateColumn) {
            return null;
        }
        $formulaInfo = Formula::where("id", $formulaId)->first();
        if (!$formulaInfo) {
            return null;
        }
        if (str_contains($formulaInfo->formula, '{getEquivalences}')) {
            $consolidatedMap = ConsolidateMapping::where("cycle_id", $cycle->id)
                                    ->where('column_name',$consolidateColumn)
                                    ->first();
            if (!$consolidatedMap) {
                return null;
            }
            if (!$consolidatedMap->formula_id) {
                return null;
            }

        }
        return null;

    }
}
