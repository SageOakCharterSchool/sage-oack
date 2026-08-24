@extends('layouts.admin')

@section('template_title')
    Resource
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Learning Center') }}
                            </span>

                            <div class="float-right">
                                @if (Auth::user()->role_as == 1)
                                    <a href="{{ route('resources.create') }}" class="btn btn-primary btn-sm float-right"
                                        data-placement="left">
                                        {{ __('Create New') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <!-- Carousel wrapper -->
                    <div id="carouselMultiItemExample" data-mdb-carousel-init
                        class="carousel slide carousel-dark text-center mt-3" data-mdb-ride="carousel">

                        <!-- Inner -->
                        <div class="carousel-inner py-4">
                            <!-- Single item -->
                            <div class="carousel-item active">
                                <div class="container">
                                    <div class="row">
                                        @foreach ($resources as $resource)
                                            <div class="col-lg-4">

                                                <div class="card mt-2">
                                                    <a href="javascript:view_resource({{$resource->id}},'{{$resource->resource}}','{{ $resource->resource_url }}')">
                                                        <img src="{{ \App\Models\Resource::getImageByPath($resource->resource_thumbnail) }}"
                                                            class="card-img-top" width="300px" height="200px"
                                                            alt="Waterfall" />
                                                    </a>
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $resource->resource }}</h5>
                                                        <p class="card-text">
                                                            {{ $resource->description }}
                                                        </p>
                                                        <div class="btn-group">

                                                            <a href="javascript:view_resource({{$resource->id}},'{{$resource->resource}}','{{ $resource->resource_url }}')"
                                                                class="btn btn-primary">View</a>
                                                            @if (Auth::user()->role_as == 1)
                                                                <a href="{{ route('resources.edit', $resource->id) }}"
                                                                    class="btn btn-info">Edit</a>
                                                                <form
                                                                    action="{{ route('resources.destroy', $resource->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">
                                                                        {{ __('Delete') }}</button>
                                                            @endif
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- Inner -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="resourceModalShow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="height: 32rem">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Resource View <span id="resourcesName">Resource 1</span></h5>
                    <button type="button" class="close" onclick="closeResourcesShowModal()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="resourceIFrame" src="" frameborder="0" width="100%" height="100%" ></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeResourcesShowModal()" data-dismiss="modal">Close</button>

                </div>
            </div>

        </div>
    </div>
@endsection
@push('script')
    <script>
        function view_resource(resourceId, resourceName, resourceURL) {
            $("#resourcesName").html(resourceName);
            $("#resourceIFrame").attr("src", resourceURL);
            $('#resourceModalShow').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#resourceModalShow').modal("show");
        }

        function closeResourcesShowModal() {
            $('#resourceModalShow').modal("hide");
        }


    </script>
@endpush

