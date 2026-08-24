@extends('layouts.admin')

@section('template_title')
    {{ __('Update') }} Consolidate Color
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">
                @include("layouts.includes.admin._messages")
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Consolidate Color</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('consolidate-colors.update', $masterTableColor->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('consolidate-color.form')
                            <input type="hidden" name="form_status" value="E">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
