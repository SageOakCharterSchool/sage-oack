<?php

use App\Http\Controllers\Admin\ChrometrackingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\BuildReportController;
use App\Http\Controllers\Admin\TablesFieldsDefinitionController;
use App\Http\Controllers\Admin\TablesDefinitionController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Admin\Consolidated\ShowConsolidated;
use App\Http\Controllers\MailController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ImpersonateController;
use App\Http\Controllers\ConsolidateMappingController;
use App\Http\Controllers\FormulaController;
use App\Http\Controllers\SpecialistStudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

\DB::listen(function($sql) {

    //        $fp = fopen('C:\wamp64\www\GoodLifeProd\GoodLife\public\debug_logs\sql_' . time() . '.txt', 'a+');
    //        fwrite($fp, $sql->sql.PHP_EOL);
    //        fwrite($fp, json_encode($sql->bindings).PHP_EOL);
    //        fclose($fp);
    //        echo "==========<br>".($sql->sql);$x = print_r($sql->bindings, true);echo "<pre>$x</pre>";
});
\DB::connection()->enableQueryLog();
Route::get('/', function () {
    return view('welcome');
});

//Auth::routes(['register' => true]);
Auth::routes();
$users = User::all();
if ($users->count() >= (float)getenv('MAX_USERS') ) {
    Route::match(['get', 'post'], 'register', function(){
        return redirect('/');
    });
}

Route::get('/logmeout', [LogoutController::class,'logout'])->name('logmeout');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
->middleware('auth','isUser')
->name('home');

Route::get('/verify/{token}', [VerifyController::class,'VerifyEmail'])->name('verify');

Route::prefix('admin')->middleware(['auth','blockIP'])->group(function() {
    // Impersonate Users
    Route::get('/impersonate/{user}', [ImpersonateController::class, 'impersonate'])->name('admin-impersonate-users');
});
Route::prefix('admin')->middleware(['auth','isAdmin','blockIP'])->group(function() {

    Route::get('test-Me', [TestController::class, 'testMe'])->middleware('auth','isAdmin');
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

    // Cycle Routes
    Route::controller(App\Http\Controllers\Admin\CycleController::class)->group(function() {
        Route::get('/cycle','index');
        Route::get('/cycle/create','create');
        Route::post('/cycle','store');
        Route::get('/cycle/{cycle}/edit','edit');
        Route::put('/cycle/{cycle}','update');
    });
    // Tables Definition
    Route::controller(TablesDefinitionController::class)->group(function() {
        Route::get('/table-def','index');
        // Route::get('/table-def/create','create');
        Route::get('/table-def/build-formulas/{tableId}','buildFormulas');
        Route::post('/table-def/build-formulas-store/{tableId}','buildFormulasStore');
        Route::get('/table-def/clone-tables','cloneTables');
        Route::post('/table-def/clone-tables-store','cloneTablesStore');
        Route::post('/table-def/store','store');
        Route::post('/table-def/upload','uploadFiles');
        Route::get('/table-def/{tablefield}/edit','edit');
        Route::put('/table-def/{tablefield}','update');
        Route::post('/table-def/get-last-mapping','getLastMapping');
    });
    // Tables Fields Definition
    Route::controller(TablesFieldsDefinitionController::class)->group(function() {
        Route::get('/field-def/{table}/fields','index');
        // Route::get('/field-def/{table}/create','create');
        Route::post('/field-def/{table}/store','store');
        Route::get('/field-def/{table}/{tablefield}/edit','edit');
        Route::put('/field-def/{table}/{tablefield}/update','update');
        Route::get('/table-def/consolidate-mapping','consolidateMapping');
        // Route::delete('/field-def/delete','delete');
    });
    // Users Routes
    Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function() {
        Route::get('/impersonate-list','impersonateList');
        Route::get('/user','index');
        Route::get('/user/{user}/edit','edit');
        Route::get('/user/{user}/show-students-feed','getUsersForTeacherFromFeeders');
        Route::put('/user/{user}','update');
        Route::post('/user/reset-student-password', 'resetStudentPassword');
        Route::post('/user/delete-student-account', 'deleteStudentAccount');
        Route::post('/user/create-student-account', 'createStudentAccount');
        Route::post('/user/get-students-teacher-info', 'getStudentsTeacherInfo');
        Route::post('/user/get-students-specialist-info', 'getStudentsSpecialistInfo');
        Route::post('/user/reassign-student-teacher', 'reassignStudentTeacher');
        Route::post('/user/reassign-student-specialist', 'reassignStudentSpecialist');
    });
    // ProcessFiles Routes
    // Route::controller(App\Http\Controllers\Admin\ProcessFilesController::class)->group(function() {
    //     Route::get('/upload','index');
    //     Route::post('/uploadfile','uploadfile');
    //     Route::get('/process-files','processFiles');
    //     Route::post('/start-process-file','startProcessFile');
    //     Route::post('/start-process-all-file','startProcessAllFile');
    //     Route::get('/consolidate','consolidate');
    //     Route::get('/export-file-info/{table}','exportFileInfoToCSV');
    //     Route::post('/consolidate-all-files','consolidateAllFiles');
    // });
    //

    // Consolidate Mapping
    Route::resource('consolidate-mappings', ConsolidateMappingController::class);
    Route::get('/submit-consolidated-generation',[ConsolidateMappingController::class, 'consolidatedGeneration']);
    Route::get('/consolidate-view/{cycle_id?}',[ConsolidateMappingController::class, 'consolidatedView'])->name('consolidate-view');
    Route::get('/consolidate-view-csv/{cycle_id?}',[ConsolidateMappingController::class, 'consolidatedViewCSV'])->name('consolidate-view-csv');
    Route::post('/consolidate-view/{cycle_id?}',[ConsolidateMappingController::class, 'consolidatedView'])->name('consolidate-search');

    // Formulas
    Route::resource('formulas', FormulaController::class);

    // Reports
    Route::resource('build-reports', BuildReportController::class);

    // Specialists
    Route::resource('specialist-students', SpecialistStudentController::class);
    //
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    // Route::get("/no-reports", [ReportController::class,'showErrors']);
    // Route::get("/generate-individual-report/{id?}", [ReportController::class,'generateIndividualReport']);
    // Route::get("/generate-consolidated-report", [ReportController::class,'generateConsolidatedReport']);
    // Route::get("/email-individual-report/{id?}", [ReportController::class,'emailIndividualReport']);
    // Route::get("/send-email", [MailController::class,'sendMail']);
    // Route::get("/reports", [ReportController::class,'index']);


});

Route::post('/bug', [App\Http\Controllers\HomeController::class, 'bug'])->name('bug');
Route::get('/send-email', [TestController::class, 'sendEmail'])->middleware('auth');

Route::prefix('admin')->middleware(['auth','blockIP'])->group(function() {

    Route::get('/view-consolidated',ShowConsolidated::class)->name('view-consolidated');
    Route::get("/no-reports", [ReportController::class,'showErrors']);
    Route::get("/generate-individual-report/{id?}", [ReportController::class,'generateIndividualReport']);
    Route::get("/generate-consolidated-report/{cycle?}", [ReportController::class,'generateConsolidatedReport']);
    Route::get("/generate-consolidated-report-csv", [ReportController::class,'generateConsolidatedReportCSV']);
    Route::get("/generate-analysis-report/{id}", [ReportController::class,'generateAnalysisReport']);
    Route::get("/email-individual-report/{id?}", [ReportController::class,'emailIndividualReport']);
    //Route::get("/reports", [ReportController::class,'index']);
    Route::match(['GET','POST'],"/view-students", [ReportController::class,'ListStudents']);
    Route::get("/view-students", [ReportController::class,'ListStudents']);
    Route::get("/view-report/{id}/{cycle?}", [ReportController::class,'ViewReport']);
    Route::get("/chrome-tracking", [ChrometrackingController::class,'index']);


});
Route::prefix('user')->middleware(['auth','isUser','blockIP'])->group(function() {


    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'index']);
    // Impersonate Reverse
    Route::get('/unimpersonate', [ImpersonateController::class, 'unimpersonate'])->name('provider-unimpersonate-users');

    Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function() {
        Route::get('/user','index');
    });


});

Route::get('/my-info', [App\Http\Controllers\MyInfoController::class, 'index'])->middleware('auth');

Route::get('/registration', [RegistrationController::class, 'index'])->middleware('guest')->name('register');
Route::post('/registerme', [RegistrationController::class, 'registerme'])->middleware('guest')->name('registerme');

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
