<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="resource" class="form-label">{{ __('Resource') }}</label>
            <input type="text" name="resource" class="form-control @error('resource') is-invalid @enderror" value="{{ old('resource', $resource?->resource) }}" id="resource" placeholder="Resource">
            {!! $errors->first('resource', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="resource_url" class="form-label">{{ __('Resource Url') }}</label>
            <input type="text" name="resource_url" class="form-control @error('resource_url') is-invalid @enderror" value="{{ old('resource_url', $resource?->resource_url) }}" id="resource_url" placeholder="Resource Url">
            {!! $errors->first('resource_url', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" placeholder="Description">{{ old('description', $resource?->description) }}</textarea>
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="thumbnail" class="form-label">{{ __('Thumbnail') }}</label>
            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" value="{{ old('thumbnail') }}" id="thumbnail" placeholder="Thumbnail">
            {!! $errors->first('thumbnail', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            <i>
                 <span style="font-size: 10px; color:blue">If nothing selected a random image will be associated</span>
            </i>
        </div>

        <input type="hidden" name="created_by"  id="created_by" value="{{Auth::id()}}">
        <input type="hidden" name="status"  id="status" value="1">


    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
