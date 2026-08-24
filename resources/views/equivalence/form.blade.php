<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="equivalence" class="form-label">{{ __('Equivalence') }}</label>
            <input type="text" name="equivalence" class="form-control @error('equivalence') is-invalid @enderror" value="{{ old('equivalence', $equivalence?->equivalence) }}" id="equivalence" placeholder="Equivalence">
            {!! $errors->first('equivalence', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="value" class="form-label">{{ __('Value') }}</label>
            <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" value="{{ old('value', $equivalence?->value) }}" id="value" placeholder="Value">
            {!! $errors->first('value', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color" class="form-label">{{ __('Color') }}</label>
            <input style="width:200px;height:100px;" type="color" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $equivalence?->color) }}" id="color" placeholder="Color">
            {!! $errors->first('color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <input type="hidden" name="created_by" value="{{ \Auth::user()->id }}">
        <input type="hidden" name="id" value="{{ $equivalence?->id }}">

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
