<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReportPermission extends Model
{
    use HasFactory;

    protected $table = 'report_permissions';
    protected $fillable = [
        'cycle_id',
        'report_id',
        'permissions',
        'created_by',
    ];

    protected function getReportPermissions( $reportId) {
        $reportId = (int)trim($reportId);
        $cycle = Cycle::getCurrentCycle();
        //\DB::enableQueryLog();
        //$reportId = 9;
        $sql = "select * from report_permissions where cycle_id = " . $cycle->id . " and report_id = " . $reportId;
        $return  = \DB::select($sql);
        //dd($return[0]);
        if (empty($return)) {
            return false;
        }

        return $return[0];
    }

    protected function saveReportPermissions($request) {
        $cycle = Cycle::getCurrentCycle();

        $reportPermissions = $this->where('cycle_id',$cycle->id)
                                ->where('report_id',$request->reportId)
                                ->first();

        if (!$reportPermissions) {
            $data = [
                'cycle_id' => $cycle->id,
                'report_id' => $request->reportId,
                'permissions' => $request->permissions,
                'created_by' => \Auth::user()->id,
            ];
            $this->create($data);
        } else {
            $reportPermissions->permissions = $request->permissions;
            $reportPermissions->save();
        }
    }

    protected function evaluatePermissions($reportId) {

        if (\Auth::user()->role_as == 1) { // admins
            return true;
        }
        $cycle = Cycle::getCurrentCycle();
        $sql = "select * from report_permissions where cycle_id = " . $cycle->id . " and report_id = " . $reportId;
        $tmp = \DB::select($sql);
        Log::info("Report Id " . $reportId . " SQL = $sql  rows " . json_encode($tmp));
        $reportPermissions = ($tmp[0]);
        //dd($reportPermissions);
        if (!$reportPermissions) {  // no permissions for that report
            return false;
        }
        if ($reportPermissions->permissions == "") {
            return false; // no permissions for that report
        }
        $reportPermissionsArray = array_flip(explode(",",$reportPermissions->permissions));
        if (!isset($reportPermissionsArray[\Auth::user()->role_as])) {
            return false; // no permissions for that report for current user
        }
        return true;
    }
}
