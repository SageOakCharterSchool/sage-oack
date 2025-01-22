<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadFilesLog extends Model
{
    use HasFactory;

    protected $table = "upload_files_logs";
    protected $fillable = [
        'cycle_id',
        'table_id',
        'total_records',
        'file_name',
        'file_contents',
        'uploaded_by',
    ];

    protected function getLastUploadInfo($cycleId,$tableId) {
        return $this->where("cycle_id",$cycleId)
                    ->where("table_id",$tableId)
                    ->latest('id')
                    ->first();
    }
}
