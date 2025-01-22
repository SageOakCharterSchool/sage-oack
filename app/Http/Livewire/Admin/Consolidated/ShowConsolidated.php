<?php

namespace App\Http\Livewire\Admin\Consolidated;

use App\Models\Consolidate3;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Cycle;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogActivity;
use App\Models\FileUploads;
use App\Models\GlobalActions;

class ShowConsolidated extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cycle;
    public $cycleId;
    public $studentId;
    public $encId;
    public $search = "";
    public $plainId = "";
    public $encryptedSearch = "";
    public $counts = [];
    public $modal_open = false;


    public function showStudentCounts($consolidatedId) {


        LogActivity::addToLog('View Student Counts');
        $allTablesPerStudent = [];
        $cycle = Cycle::getCurrentCycle();
        if ($cycle) {
            if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
                $consolidated = Consolidate3::where('cycle_id',$cycle->id)
                        ->where('id',$consolidatedId)
                        ->first();
            } else {
                $consolidated = Consolidate3::where('cycle_id',$cycle->id)
                        ->where('id',$consolidatedId)
                        ->where('teacher_id',\Auth::user()->getTeacherId())
                        ->first();
            }
            if ($consolidated) {
                $this->studentId = $consolidated->student_id;
                $result = FileUploads::generateReport($consolidated->id);

                foreach ($result[0] as $k => $table) {
                    $this->counts[$k] = ($table) ? "ok" : "no records";
                }


            }
        }

    }

    public function closeShowCountsModal() {
        $this->counts = null;
        $this->studentId = null;
    }

    public function mount($urlslug = null)
    {
        $this->cycle = Cycle::getCurrentCycle();
        if (!$this->cycle) {
            $fileErrors = "No available cycle to run ";
            return redirect('admin/cycle')->with('error-message', $fileErrors);
        }
        $this->cycleId = $this->cycle->id;
    }

    public function render()
    {
        $this->plainId = "";
        $this->encryptedSearch = "";
        if (!empty($this->search)) {
            if (getenv("APP_ENV") == "PROD" || getenv("IS_TESTING")=="Y") {
                $this->plainId = (trim($this->search));
                $this->encryptedSearch = encrypt(trim($this->search));
            } else {
                $this->plainId = (trim($this->search));
                $this->encryptedSearch = (trim($this->search));
            }
            //dd($this->plainId,$this->encryptedSearch);
            if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
                $rows = Consolidate3::where('cycle_id',$this->cycleId)
                      ->where(function($query)
                        {
                            $query->where('student_id','like','%' . $this->plainId . '%')
                                ->orWhere('teacher_id','like','%' . $this->plainId . '%')
                                ->orWhere('column_b','like','%' . $this->plainId . '%')
                                ->orWhere('column_c','like','%' . $this->plainId . '%');
                            //$query->orWhere('teacher_id','like','%' . $this->plainId . '%');
                                // ->orWhere('column_b','like','%' . $this->encryptedSearch . '%')
                                // ->orWhere('column_c','like','%' . $this->encryptedSearch . '%');
                        })
                        ->orderBy('column_b','DESC')->paginate(50);
            } else {
                $rows = Consolidate3::where('cycle_id',$this->cycleId)
                            ->where('teacher_id',\Auth::user()->getTeacherId())
                            ->where(function($query)
                            {
                                $query->where('student_id','like','%' . $this->plainId . '%')
                                    ->orWhere('column_b','like','%' . $this->plainId . '%')
                                    ->orWhere('column_c','like','%' . $this->plainId . '%');
                                    // ->orWhere('column_b','like','%' . $this->encryptedSearch . '%')
                                    // ->orWhere('column_c','like','%' . $this->encryptedSearch . '%');
                            })
                            ->orderBy('column_b','DESC')->paginate(50);
            }
        } else {
            if (\Auth::user()->role_as == 1 || \Auth::user()->role_as == 3) {
                $rows = Consolidate3::where('cycle_id',$this->cycleId)
                        ->where('student_id','<>','')
                        ->orderBy('column_b','DESC')->paginate(50);
            } else {
                //dd(\Auth::user()->getTeacherId());
                $rows = Consolidate3::where('cycle_id',$this->cycleId)
                        ->where('student_id','<>','')
                        ->where('teacher_id',\Auth::user()->getTeacherId())
                        ->orderBy('column_b','DESC')->paginate(50);
            }
        }
        //$tableColumnInfo = DB::select('SHOW FULL COLUMNS FROM consolidateds');
        $headers= [
            ['header' => 'id','field'=>'id'],
            ['header' => 'teacher_id','field'=>'teacher_id'],
            ['header' => 'cycle_id','field'=>'cycle_id'],
            ['header' => 'Student ID','field'=>'student_id'],
            ['header' => 'Student Last Name','field'=>'column_b'],
            ['header' => 'Student First Name','field'=>'column_c'],
            ['header' => 'Grade','field'=>'column_d'],
            ['header' => 'SIS','field'=>'column_e'],
            ['header' => 'Program','field'=>'program'],
            ['header' => 'Teacher Name','table' => 'teacher_students','field'=>'column_g'],
            ['header' => 'Qualifying Subject 1','field'=>'column_f'],
            ['header' => 'Qualifying Subject 2','field'=>'column_h'],
            ['header' => 'ELA Intervention Recommendation','field'=>'column_i'],
            ['header' => 'ELA Intervention Selected','field'=>'column_k'],
            ['header' => 'Math Intervention Recommendation','field'=>'column_j'],
            ['header' => 'MATH Intervention Selected','field'=>'column_l'],
            ['header' => 'CAASPP Math','field'=>'caaspp_math'],
            ['header' => 'CAASPP Reading','field'=>'caaspp_reading'],
            ['header' => 'iReady Math Points Fall','field'=>'column_m'],
            ['header' => 'iReady Relative Placement Math Fall','field'=>'column_n'],
            ['header' => 'iReady Level Math Fall','field'=>'column_o'],
            ['header' => 'iReady Reading Points Fall','field'=>'column_p'],
            ['header' => 'iReady Relative Placement Reading Fall','field'=>'column_q'],
            ['header' => 'iReady Level Reading Fall','field'=>'column_r'],
            ['header' => 'iReady Math Points Mid Year','field'=>'column_s'],
            ['header' => 'iReady Relative Placement Math Mid Year','field'=>'column_t'],
            ['header' => 'iReady Level Math Mid Year','field'=>'column_u'],
            ['header' => 'iReady Reading Points Mid Year','field'=>'column_v'],
            ['header' => 'iReady Relative Placement Reading Mid Year','field'=>'column_w'],
            ['header' => 'iReady Level Reading Mid Year','field'=>'column_x'],
            ['header' => 'iReady Math Points End of Year','field'=>'column_y'],
            ['header' => 'iReady Relative Placement Math End of Year','field'=>'column_z'],
            ['header' => 'iReady Level Math End of Year','field'=>'column_aa'],
            ['header' => 'iReady Reading Points End of Year','field'=>'column_ab'],
            ['header' => 'iReady Relative Placement Reading End of Year','field'=>'column_ac'],
            ['header' => 'iReady Level Reading End of Year','field'=>'column_ad'],
            ['header' => 'iReady Growth Points Math Mid Year','field'=>'column_ae'],
            ['header' => 'iReady Levels Math Growth Mid Year','field'=>'column_af'],
            ['header' => 'iReady Growth Points Reading Mid Year','field'=>'column_ag'],
            ['header' => 'iReady Levels Reading Growth Mid Year','field'=>'column_ah'],
            ['header' => 'iReady Growth Points Math End of Year','field'=>'column_ai'],
            ['header' => 'IReady Levels Math Growth End of Year','field'=>'column_aj'],
            ['header' => 'iReady Growth Points Reading End of Year','field'=>'column_ak'],
            ['header' => 'IReady Levels Reading Growth End of Year','field'=>'column_al'],
            // ['header' => 'FLUENCY Percentile','field'=>'column_ap'],
            // ['header' => 'VOCAB Percentile','field'=>'column_aq'],
            // ['header' => 'PROF Passage Reading','field'=>'column_ar'],
            // ['header' => 'letter name accuracy','field'=>'column_as'],
            // ['header' => 'letter sound accuracy','field'=>'column_at'],
            // ['header' => 'word accuracy','field'=>'column_au'],
            // ['header' => 'phoneme accuracy','field'=>'column_av'],
            ['header' => 'Reading Risk','field'=>'column_at'],
            //['header' => 'PROF MATH PERCENTILE','field'=>'column_ax'],
            ['header' => 'Math Risk','field'=>'column_av'],

            // ['header' => 'Progress Monitoring Test Given','field'=>'column_az'],
            // ['header' => 'Progress Monitoring Accuracy Percentile','field'=>'column_ba'],
            // ['header' => 'STAR Assessment Math Fall','field'=>'column_bb'],
            // ['header' => 'STAR Assessment Reading Fall','field'=>'column_bc'],
            // ['header' => 'STAR Assessment Math Mid Year','field'=>'column_bd'],
            // ['header' => 'STAR Assessment Reading Mid Year','field'=>'column_be'],
            // ['header' => 'STAR Assessment Math End of Year','field'=>'column_bf'],
            // ['header' => 'STAR Assessment Reading End of Year','field'=>'column_bg'],
            // ['header' => 'STAR Assessment GROWTH Math Mid Year','field'=>'column_bh'],
            // ['header' => 'STAR Assessment GROWTH Reading Mid Year','field'=>'column_bi'],
            // ['header' => 'STAR Assessment GROWTH Math End of Year','field'=>'column_bj'],
            // ['header' => 'STAR Assessment GROWTH Reading End of Year','field'=>'column_bk'],
            ['header' => 'Intervention Class Attendance','field'=>'column_bi'],
            ['header' => 'iReady Minutes Math','field'=>'column_bj'],
            ['header' => 'iReady Minutes Reading','field'=>'column_bk'],
            // ['header' => 'FRECKLE MINUTES MATH','field'=>'column_bo'],
            // ['header' => 'FRECKLE MINUTES READING','field'=>'column_bp'],
            ['header' => 'Reading Class Minutes','field'=>'column_bn'],
            ['header' => 'Math Class Minutes','field'=>'column_bp'],
            ['header' => 'Tutor.com Sessions','field'=>'tutorcom'],

            //['header' => 'Math 180 Minutes','field'=>'column_bs'],
            ['header' => 'Class Info','field'=>'column_bq'],
            ['header' => 'Notes','field'=>'column_br'],
            //['header' => 'Math Class Minutes','field'=>'column_bv'],
            ['header' => 'SST','field'=>'column_bt'],
            ['header' => 'sped','field'=>'column_bu'],
            ['header' => 'ELD','field'=>'column_bv'],
        ];

        //dd($tableColumnInfo);

        if (Auth::user()->role_as == 0) {
            return view('livewire.admin.consolidated.show-consolidated', ['rows' => $rows, 'headers'=> $headers,'cycle'=>$this->cycle])
                    ->layout('layouts.user');
        } else {
            return view('livewire.admin.consolidated.show-consolidated', ['rows' => $rows, 'headers'=> $headers,'cycle'=>$this->cycle])
                    ->layout('layouts.admin');
        }
    }
}
