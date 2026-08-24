<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Http\Requests\ResourceRequest;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;


/**
 * Class ResourceController
 * @package App\Http\Controllers
 */
class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = Resource::paginate();

        return view('resource.index', compact('resources'))
            ->with('i', (request()->input('page', 1) - 1) * $resources->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!\Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }

        $resource = new Resource();
        return view('resource.create', compact('resource'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResourceRequest $request)
    {

        $data = $request->validated();

        if (!$request->hasFile('thumbnail')) {
            $imageUrl = file_get_contents("https://picsum.photos/200/300");
            Log::info("Image URL " . $imageUrl);
            $image = Image::read($imageUrl);
            $imageName = $request->resource . ".jpg";
            Log::info("Image Name " . $imageName);
        } else {
            $file = $request->file('thumbnail');
            //$filename = $file->getClientOriginalName();
            $imageName = $request->resource . ".jpg";
            $imageUrl = file_get_contents($file);
        }

        $path = Storage::disk('s3')->put('sip-resources/'. $imageName, $imageUrl);
        Log::info("S3 Path1 " . $path);
        $path = Storage::disk('s3')->url($path);
        Log::info("S3 Path2 " . $path);

        $data['resource_thumbnail'] = 'sip-resources/'. $imageName;
        Resource::create($data);

        return redirect()->route('resources.index')
            ->with('success', 'Resource created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!\Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        $resource = Resource::find($id);

        return view('resource.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!\Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        $resource = Resource::find($id);

        return view('resource.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceRequest $request, Resource $resource)
    {

        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            //$filename = $file->getClientOriginalName();
            $imageName = $request->resource . ".jpg";
            $imageUrl = file_get_contents($file);
            $path = Storage::disk('s3')->put('sip-resources/'. $imageName, $imageUrl);
            $path = Storage::disk('s3')->url($path);

            $data['resource_thumbnail'] = 'sip-resources/'. $imageName;
        }

        $resource->update($data);

        return redirect()->route('resources.index')
            ->with('success', 'Resource updated successfully');
    }

    public function destroy($id)
    {
        if (!\Auth::user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        Resource::find($id)->delete();

        return redirect()->route('resources.index')
            ->with('success', 'Resource deleted successfully');
    }
}
