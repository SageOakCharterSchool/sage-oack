<?php

namespace App\Observers;

use App\Models\TeacherStudent;
use App\Models\Cycle;
use App\Models\GlobalActions;


class TeacherStudentObserver
{
    /**
     * Handle the TeacherStudent "created" event.
     *
     * @param  \App\Models\TeacherStudent  $teacherStudent
     * @return void
     */
    public function created(TeacherStudent $teacherStudent)
    {

        $cycle = Cycle::getCurrentCycle();
        if (!$cycle ) {
            return false;
        }
        $models = GlobalActions::getModelNames();
        $students = explode(",",$teacherStudent->students_list);

        foreach ($students as $studentID) {
            foreach ($models as $model) {
                $myModel = "\App\Models\\$model";
                $myModel::UpdateTeacherIdToItsStudents($teacherStudent->teacher_id,$studentID,$cycle);
            }
        }
    }

    /**
     * Handle the TeacherStudent "updated" event.
     *
     * @param  \App\Models\TeacherStudent  $teacherStudent
     * @return void
     */
    public function updated(TeacherStudent $teacherStudent)
    {
        //
    }

    /**
     * Handle the TeacherStudent "deleted" event.
     *
     * @param  \App\Models\TeacherStudent  $teacherStudent
     * @return void
     */
    public function deleted(TeacherStudent $teacherStudent)
    {
        //
    }

    /**
     * Handle the TeacherStudent "restored" event.
     *
     * @param  \App\Models\TeacherStudent  $teacherStudent
     * @return void
     */
    public function restored(TeacherStudent $teacherStudent)
    {
        //
    }

    /**
     * Handle the TeacherStudent "force deleted" event.
     *
     * @param  \App\Models\TeacherStudent  $teacherStudent
     * @return void
     */
    public function forceDeleted(TeacherStudent $teacherStudent)
    {
        //
    }


}
