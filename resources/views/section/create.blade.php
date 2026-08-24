@extends('layouts.admin')

@section('template_title')
    {{ __('Create') }} Section
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Section</span>
                    </div>
                    <div class="card-body ">
                        <form method="POST" action="{{ route('sections.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('section.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
