<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Report
 *
 * @property $id
 * @property $cycle_id
 * @property $report_name
 * @property $report_description
 * @property $report
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Report extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cycle_id', 'report_name', 'report_description', 'report'];



    protected function cloneReportsIntoNewCycle($cycleFrom, $cycleTo,$clonedTables,$clonedFormulas) {
        $this->where("cycle_id",$cycleTo)->delete(); // remove all formulas for new cycle
        //dd($cycleFrom,$cycleTo);
        $reports = $this->where("cycle_id",$cycleFrom)
                        ->get();
        foreach ($reports as $report) {
            $newReport = $report->replicate();
            $newReport->cycle_id = $cycleTo;
            $newReport->save();
        }
    }

}
