<?php

use App\Models\Equivalences;
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
        Schema::create('equivalences', function (Blueprint $table) {
            $table->id();
            $table->string('equivalence')->index();
            $table->string('value', 25)->nullable();
            $table->string('color', 15)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
        $equivalences['Emerging K'] = -1;
        $equivalences['Early K'] =    0;
        $equivalences['Mid K'] = 0;
        $equivalences['Late K'] = 0;
        $equivalences['Level K'] = 0;
        $equivalences['Early 1'] = 1;
        $equivalences['Mid 1'] = 1;
        $equivalences['Late 1'] = 1;
        $equivalences['Level 1'] = 1;
        $equivalences['Early 2'] = 2;
        $equivalences['Mid 2'] = 2;
        $equivalences['Late 2'] = 2;
        $equivalences['Level 2'] = 2;
        $equivalences['Early 3'] = 3;
        $equivalences['Mid 3'] = 3;
        $equivalences['Late 3'] = 3;
        $equivalences['Level 3'] = 3;
        $equivalences['Early 4'] = 4;
        $equivalences['Mid 4'] = 4;
        $equivalences['Late 4'] = 4;
        $equivalences['Level 4'] = 4;
        $equivalences['Early 5'] = 5;
        $equivalences['Mid 5'] = 5;
        $equivalences['Late 5'] = 5;
        $equivalences['Level 5'] = 5;
        $equivalences['Early 6'] = 6;
        $equivalences['Mid 6'] = 6;
        $equivalences['Late 6'] = 6;
        $equivalences['Level 6'] = 6;
        $equivalences['Early 7'] = 7;
        $equivalences['Mid 7'] = 7;
        $equivalences['Late 7'] = 7;
        $equivalences['Level 7'] = 7;
        $equivalences['Early 8'] = 8;
        $equivalences['Mid 8'] = 8;
        $equivalences['Late 8'] = 8;
        $equivalences['Level 8'] = 8;
        $equivalences['Level 9'] = 9;
        $equivalences['Early 9'] = 9;
        $equivalences['Mid 9'] = 9;
        $equivalences['Level 10'] = 10;
        $equivalences['Early 10'] = 10;
        $equivalences['Mid 10'] = 10;
        $equivalences['Level 11'] =    11;
        $equivalences['Early 11'] = 11;
        $equivalences['Mid 11'] = 11;
        $equivalences['Early Algebra 1'] = 9;
        $equivalences['Mid Algebra 1'] = 9;
        $equivalences['Late Algebra 1'] = 9;
        $equivalences['Algebra 1'] = 9;
        $equivalences['Early Geometry'] = 10;
        $equivalences['Mid Geometry'] = 10;
        $equivalences['Late Geometry'] = 10;
        $equivalences['Geometry'] = 10;
        $equivalences['Early Algebra 2'] = 11;
        $equivalences['Mid Algebra 2'] = 11;
        $equivalences['Late Algebra 2'] = 11;
        $equivalences['Algebra 2'] = 11;
        $equivalences['Early CCR Math'] = 9;
        $equivalences['Mid CCR Math'] = 9;
        $equivalences['Late CCR Math'] = 9;
        $equivalences['CCR Math'] = 9;
        //New Equivalences
        $equivalences['Grade K'] = 0;
        $equivalences['Grade 1'] = 1;
        $equivalences['Grade 2'] = 2;
        $equivalences['Grade 3'] = 3;
        $equivalences['Grade 4'] = 4;
        $equivalences['Grade 5'] = 5;
        $equivalences['Grade 6'] = 6;
        $equivalences['Grade 7'] = 7;
        $equivalences['Grade 8'] = 8;
        $equivalences['Grade 9'] = 9;
        $equivalences['Grade 10'] = 10;
        $equivalences['Grade 11'] = 11;
        $equivalences['Grade 12'] = 12;
        foreach ($equivalences as $k => $equivalence) {
            $data = [
                'equivalence' => $k,
                'value' => $equivalence,
                'color' => null,
                'created_by' => 1
            ];
            Equivalences::create($data);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equivalences');
    }
};
