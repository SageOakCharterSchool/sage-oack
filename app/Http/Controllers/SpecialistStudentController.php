<?php

namespace App\Http\Controllers;

use App\Models\SpecialistStudent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SpecialistStudentRequest;
use App\Models\Cycle;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SpecialistStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cycle = Cycle::getCurrentCycle();
        SpecialistStudent::createSpecialistFromUsers();
        $specialistStudents = SpecialistStudent::where("cycle_id",$cycle->id)
                                    ->whereNotNull('student_id')
                                    ->paginate(15);
        //dd($specialistStudents);
        return view('specialist-student.index', compact('specialistStudents','cycle'))
            ->with('i', ($request->input('page', 1) - 1) * $specialistStudents->perPage());
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {

        $specialistStudent = new SpecialistStudent();

        return view('specialist-student.create', compact('specialistStudent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpecialistStudentRequest $request): RedirectResponse
    {
        $validateMe = $request->validated();
        $cycle =  Cycle::getCurrentCycle();
        $validateMe['cycle_id'] = $cycle->id;
        $validateMe['created_by'] = \Auth::user()->id;
        $validateMe['name'] = $request->first_name . ' ' . $request->last_name;
        $user = User::where('email',$request->email)->first();
        $validateMe['specialist_id'] = $user->id;

        SpecialistStudent::create($validateMe);

        return Redirect::route('specialist-students.index')
            ->with('success', 'SpecialistStudent created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $specialistStudent = SpecialistStudent::find($id);

        return view('specialist-student.show', compact('specialistStudent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $specialistStudent = SpecialistStudent::find($id);

        return view('specialist-student.edit', compact('specialistStudent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SpecialistStudentRequest $request, SpecialistStudent $specialistStudent): RedirectResponse
    {
        $specialistStudent->update($request->validated());

        return Redirect::route('specialist-students.index')
            ->with('success', 'SpecialistStudent updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        SpecialistStudent::find($id)->delete();

        return Redirect::route('specialist-students.index')
            ->with('success', 'SpecialistStudent deleted successfully');
    }

    public function uploadFile(): View
    {

        return view('specialist-student.upload');

    }
    public function processUploadFile(Request $request)
    {
        $request->validate([
            'specialist_file' => 'required|mimes:csv,txt|max:12048',
        ]);
        if($request->file()) {

            $fileName = time().'_'.$request->file('specialist_file')->getClientOriginalName();
            $filePath = $request->file('specialist_file')->storeAs('uploads', $fileName, 'public');
            $return = SpecialistStudent::processUploadedFile($fileName,$filePath);
            //dd($return);
            if ($return['status']) {
                return redirect('admin/specialist-students')->with('success', 'File Uploaded successfully! ' . $return['message']);
            } else {
                return redirect('admin/specialist-students')->with('error', 'Please check the file, some errors reported ' .  $return['message']);
            }

        }

    }
}
