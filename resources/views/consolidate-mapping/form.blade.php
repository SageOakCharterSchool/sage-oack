<div class="row padding-1 p-1">
    <div class="col-md-12">
        @include('layouts.includes.admin._messages')
        @if (session('message'))
            <h2 class="alert alert-success">{{ session('message') }},</h2>
        @endif
        @if (session('error-message'))
            <h2 class="alert alert-warning">{{ session('error-message') }},</h2>
        @endif

        <div class="form-group mb-2 mb20">
            <label for="screen_sort" class="form-label">{{ __('Column Sorting') }}</label>
            <input type="text" name="screen_sort" class="form-control @error('screen_sort') is-invalid @enderror"
                value="{{ old('screen_sort', $consolidateMapping?->screen_sort) }}" id="screen_sort"
                placeholder="10,20,30..etc">
            {!! $errors->first('screen_sort', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="column_name" class="form-label">{{ __('Column Name') }}</label>
            <input type="text" name="column_name" class="form-control @error('column_name') is-invalid @enderror"
                value="{{ old('column_name', $consolidateMapping?->column_name) }}" id="column_name"
                placeholder="Column_A,Column_B">
            {!! $errors->first('column_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="column_description" class="form-label">{{ __('Column Description') }}</label>
            <input type="text" name="column_description"
                class="form-control @error('column_description') is-invalid @enderror"
                value="{{ old('column_description', $consolidateMapping?->column_description) }}"
                id="column_description" placeholder="Column Description">
            {!! $errors->first(
                'column_description',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="field_source" class="form-label">{{ __('Field Source') }}</label>
            <select class="form-select" name="field_source" id="field_source">
                <option value="">Select Field to use</option>
                <option value="999->None" {{ old('field_source', $consolidateMapping?->field_source) == "999->None" ? ' selected ' : '' }}>None</option>
                @foreach ($fieldsToSelect as $row)
                    <option value="{{ $row->map_id }}"
                        {{ old('field_source', $consolidateMapping?->field_source) == $row->map_id ? ' selected ' : '' }}>
                        {{ $row->field_name }}</option>
                @endforeach
            </select>

            {!! $errors->first('field_source', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="field_source" class="form-label">{{ __('Formula Source') }}</label>
            <select class="form-select" name="formula_id" id="formula_id">
                <option value="">Select Formula to use</option>
                @foreach ($formulasToUse as $k => $row)
                    <option value="{{ $k }}"
                        {{ old('formula_id', $consolidateMapping?->formula_id) == $k ? ' selected ' : '' }}>
                        {{ $row }}</option>
                @endforeach
            </select>

            {!! $errors->first('formula_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">
        <input type="hidden" name="created_by" value="{{ \Auth::user()->id }}">

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
