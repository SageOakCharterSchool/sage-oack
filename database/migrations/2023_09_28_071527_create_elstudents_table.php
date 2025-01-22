<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elstudents', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('student_id')->index()->comment('student id')->nullable();
            $table->string('cycle_id')->comment('cycle_id')->nullable();
            $table->string('teacher_id')->comment('teacher_id')->nullable();
            $table->string('column_a')->comment('')->nullable();
            $table->string('column_b')->comment('')->nullable();
            $table->string('column_c')->comment('ELD Program Assigned')->nullable();
            $table->string('column_d')->comment('Long Term EL (LTEL)/At Risk')->nullable();
            $table->string('column_e')->comment('Student Last Name')->nullable();
            $table->string('column_f')->comment('Student First Name')->nullable();
            $table->string('column_g')->comment('Grade')->nullable();
            $table->string('column_h')->comment('SSID')->nullable();
            $table->string('column_i')->comment('90 (incl. 2 ALT) SOCS =  75  (2 ALT)      SOCS-K = 7             SOCS-S = 8')->nullable();
            $table->string('column_j')->comment('PLA VLA HS TK- No ADA')->nullable();
            $table->string('column_k')->comment('Primary Language: Spanish- 66  Arabic- 6
            French- 3
            Malayalam- 1
            Bengali- 2
            Filipino-Tagalog- 1
            Armenian- 2
            Russian- 3
            Chinese-Madarin- 1
            Japanese- 1
            Vietnamese- 2
            Indonesian- 1')->nullable();
            $table->string('column_l')->comment('Local ID')->nullable();
            $table->string('column_m')->comment('DOB')->nullable();
            $table->string('column_n')->comment('Gender ')->nullable();
            $table->string('column_o')->comment('Teacher               LAST NAME')->nullable();
            $table->string('column_p')->comment('Teacher          FIRST NAME')->nullable();
            $table->string('column_q')->comment('IEP: 13 (incl. 2 ALT)
            SOCS: 12
            SOCS-S: 1
            SOCS- K: 0')->nullable();
            $table->string('column_r')->comment('504')->nullable();
            $table->string('column_s')->comment('Parent Name')->nullable();
            $table->string('column_t')->comment('Parent Email')->nullable();
            $table->string('column_u')->comment('Date/Yr Enrolled US School')->nullable();
            $table->string('column_v')->comment('AFTER    Apr 15 US             < 1 yr ')->nullable();
            $table->string('column_w')->comment('Add to LIP (date) ')->nullable();
            $table->string('column_x')->comment('Scale Score Overall 21/22')->nullable();
            $table->string('column_y')->comment('21/22  Overall  ELPAC Level ')->nullable();
            $table->string('column_z')->comment('2023 SA Date Tested')->nullable();
            $table->string('column_aa')->comment('22/23 Overall')->nullable();
            $table->string('column_ab')->comment('22/23 Oral')->nullable();
            $table->string('column_ac')->comment('22/23 Written')->nullable();
            $table->string('column_ad')->comment('22/23        ELPAC Level ')->nullable();
            $table->string('column_ae')->comment('Scale Score Overall')->nullable();
            $table->string('column_af')->comment('Score Diff  Pos/Neg')->nullable();
            $table->string('column_ag')->comment(' Improved  ONE Level        ')->nullable();
            $table->string('column_ah')->comment('New / Returning  Student')->nullable();
            $table->string('column_ai')->comment('Enrollment Date')->nullable();
            $table->string('column_aj')->comment('RFEP Review (LL)')->nullable();
            $table->string('column_ak')->comment('At Risk (LL)')->nullable();
            $table->string('column_al')->comment('Long Term EL (LTEL) (LL)')->nullable();
            $table->string('column_am')->comment('Alert  Theresa for curriculum')->nullable();
            $table->string('column_an')->comment('*1                  Primary Language')->nullable();
            $table->string('column_ao')->comment('2                   First Language')->nullable();
            $table->string('column_ap')->comment('*3                Home Language')->nullable();
            $table->string('column_aq')->comment('4   Spoken by parent to student')->nullable();
            $table->string('column_ar')->comment('5    Spoken by parent at home')->nullable();
            $table->string('column_as')->comment('English fluency')->nullable();
            $table->string('column_at')->comment('17/18')->nullable();
            $table->string('column_au')->comment('18/19')->nullable();
            $table->string('column_av')->comment('19/20')->nullable();
            $table->string('column_aw')->comment('20/21')->nullable();
            $table->string('column_ax')->comment('21/22')->nullable();
            $table->string('column_ay')->comment('22/23')->nullable();
            $table->string('column_az')->comment('23/24')->nullable();
            $table->string('column_ba')->comment('Overall 16/17')->nullable();
            $table->string('column_bb')->comment('Overall 17/18')->nullable();
            $table->string('column_bc')->comment('Overall 18/19')->nullable();
            $table->string('column_bd')->comment('Overall 19/20')->nullable();
            $table->string('column_be')->comment('Overall  20/21')->nullable();
            $table->string('column_bf')->comment('Overall  21/22')->nullable();
            $table->string('column_bg')->comment('Overall 22/23')->nullable();
            $table->string('column_bh')->comment('Overall 23/24')->nullable();
            $table->string('column_bi')->comment('General')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elstudents');
    }
};
