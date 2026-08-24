<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="section" class="form-label">{{ __('Section') }}</label>
            <input type="text" name="section" class="form-control @error('section') is-invalid @enderror" value="{{ old('section', $section?->section) }}" id="section" placeholder="Section">
            {!! $errors->first('section', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color" class="form-label">{{ __('Color') }}</label>
            <input type="color" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $section?->color) }}" id="color" placeholder="Color" style="height: 125px; width:300px">
            {!! $errors->first('color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color" class="form-label">{{ __('Font Color') }}</label>
            <input type="color" name="font_color" class="form-control @error('color') is-invalid @enderror" value="{{ old('font_color', $section?->font_color) }}" id="font_color" placeholder="Font Color" style="height: 125px; width:300px">
            {!! $errors->first('font_color', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <input type="hidden" name="created_by"  id="created_by" value="{{Auth::id()}}">


    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
