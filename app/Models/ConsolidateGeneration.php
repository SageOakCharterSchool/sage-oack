<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsolidateGeneration extends Model
{
    use HasFactory;

    protected $table = "consolidate_generations";
    protected $fillable = [
        'status',
        'created_by',
        'num_records',
    ];

    protected function checkstatus() {
        $row = $this->latest()->first();
        if (!$row) {
            $data = [
                'status' => 0,
                'created_by' => \Auth::user()->id,
                'num_records' => 0,
            ];
            $row = $this->create($data);
        }
        return $row;
    }

    /**
     * status = 1: Completed
     * status = 2: Submitted
     * status = 3: In process
     *
     */
    protected function markGenerationAsInProcess($status) {
        $row = $this->latest()->first();

        if ($row) {
            $data = [
                'status' => $status,
            ];
            $row = $this->where("id",$row->id)->update($data);
        }
        return $row;
    }



}
