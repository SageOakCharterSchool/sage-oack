<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="column_name" class="form-label">{{ __('Field to Color') }}</label>
            @if ($columnToFilter || $form_status == "E")
                <input type="hidden" name="column_name" value="{{ ($columnToFilter) ? $columnToFilter : old('column_name', $masterTableColor?->column_name)  }}">
                <select readonly="readonly"  disabled class="form-select @error('column_name') is-invalid @enderror" name="column_name_dummy" id="column_name_dummy">
            @elseif (!$columnToFilter || $form_status == "A")
                <select class="form-select @error('column_name') is-invalid @enderror"   name="column_name" id="column_name">
                <option value="">Please select consolidate field to color</option>
            @endif
            @foreach ($tmpVariables as $row)
                <option value="{{ $row->column_name }}"
                    {{ old('column_name', $masterTableColor?->column_name) == $row->column_name  ? ' selected ' : '' }}>
                    {{ $row->field_name }}
                </option>
            @endforeach
            </select>
            {!! $errors->first('column_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="value" class="form-label">{{ __('Value') }}</label>
            <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $masterTableColor?->value) }}" id="value" placeholder="Value">
            {!! $errors->first('value', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color" class="form-label">{{ __('Font Color') }}</label>
            <input style="width:200px;height:100px;" type="color" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $masterTableColor?->color) }}" id="color" placeholder="Color">
            {!! $errors->first('color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color" class="form-label">{{ __('Background Color') }}</label>
            <input style="width:200px;height:100px;" type="color" name="background_color" class="form-control @error('background_color') is-invalid @enderror" value="{{ old('color', $masterTableColor?->background_color) }}" id="background_color" placeholder="Background Color">
            {!! $errors->first('background_color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <input type="hidden" name="id" value="{{ $masterTableColor?->id }}">
        <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">
        <input type="hidden" name="created_by" value="{{ \Auth::user()->id }}">
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
