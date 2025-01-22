<div class="row padding-1 p-1">
    <div class="col-md-12">
        @include("layouts.includes.admin._messages")
            @if (session('message'))
                <h2 class="alert alert-success"  >{{ session('message') }},</h2>
            @endif
            @if (session('error-message'))
                <h2 class="alert alert-warning"  >{{ session('error-message') }},</h2>
            @endif

        <div class="form-group mb-2 mb20">
            <label for="formula_name" class="form-label">{{ __('Formula Name') }}</label>
            @if ($formula->is_system == 0)
            <input type="text" name="formula_name" class="form-control @error('formula_name') is-invalid @enderror"
                value="{{ old('formula_name', $formula?->formula_name) }}" id="formula_name" placeholder="Formula Name">
            @else
            <input type="text" name="formula_name" class="form-control @error('formula_name') is-invalid @enderror"
                readonly value="{{ old('formula_name', $formula?->formula_name) }}" id="formula_name" placeholder="Formula Name">
            @endif
            {!! $errors->first('formula_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="formula_description" class="form-label">{{ __('Formula Description') }}</label>
            <input type="text" name="formula_description"
                class="form-control @error('formula_description') is-invalid @enderror"
                value="{{ old('formula_description', $formula?->formula_description) }}" id="formula_description"
                placeholder="Formula Description">
            {!! $errors->first(
                'formula_description',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="formula" class="form-label">{{ __('Formula') }}</label>
            <textarea rows="10" id="formula" placeholder="Examples:&#10;

                    {student_lists.column_a}[+]{math_lists.column_b} or &#10;
                    {student_lists.column_a}[-]{math_lists.column_b} or &#10;
                    {student_lists.column_a}[*]{math_lists.column_b} or &#10;
                    {student_lists.column_a}[/]{math_lists.column_b} or &#10;
                    {student_lists.column_a}[^]{math_lists.column_b} &#10;
                    &#10;&#10;
                    {student_lists.column_a}[+]100&#10;
                    {student_lists.column_a}[-]100&#10;
                    {student_lists.column_a}[*]100&#10;
                    {student_lists.column_a}[/]100&#10;
                    {student_lists.column_a}[^]100&#10;
                    &#10;&#10;
                    [remove:smith]{student_lists.column_a}&#10;
                " name="formula"
                class="form-control @error('formula') is-invalid @enderror">{{ old('formula', $formula?->formula) }}</textarea>
            {!! $errors->first('formula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            <div style="color:blue;font-size:10px">
                Examples:<br><br>
                <small>
                    {student_lists.column_a}[+]{math_lists.column_b} or <br>
                    {student_lists.column_a}[-]{math_lists.column_b} or <br>
                    {student_lists.column_a}[*]{math_lists.column_b} or <br>
                    {student_lists.column_a}[/]{math_lists.column_b} or <br>
                    {student_lists.column_a}[^]{math_lists.column_b} <br>
                    <hr>
                    {student_lists.column_a}[+]100<br>
                    {student_lists.column_a}[-]100<br>
                    {student_lists.column_a}[*]100<br>
                    {student_lists.column_a}[/]100<br>
                    {student_lists.column_a}[^]100<br>
                    <hr>
                    [remove:smith]{student_lists.column_a}<br>
                </small>
            </div>
        </div>
        <input type="hidden" name="formula_id" value="{{ $formula?->id }}">
        <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">
        <input type="hidden" name="created_by" value="{{ \Auth::user()->id }}">
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
@push('script')
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    <script>
        var siteVariables = @json($siteVariables ?? '');
        var siteOperations = @json($siteOperations ?? '');
        tinymce.init({
            // content_css: "/assets/css/app.css?v=" + myVersion,
            // menubar: false,
            selector: 'textarea#formula',
            height: 250,
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            theme: 'silver',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,
            paste_retain_style_properties: "all",
            text_patterns: false,
            toolbar2: "mybutton1|mybutton2 ",
            // extended_valid_elements: 'inline[class="blueTitles|fontSize48Weight200|fontSize60Weight700"]',
            setup: function(editor) {
                var items1 = [];
                var items2 = [];
                siteVariables.forEach(function(myField) {
                    var obj = {
                        type: 'menuitem',
                        text: myField,
                        onAction: function() {
                            editor.insertContent(myField);
                        }
                    };
                    items1.push(obj);
                }),
                editor.ui.registry.addMenuButton('mybutton1', {
                    text: 'SIP Variables',
                    fetch: function(callback) {
                        callback(items1);
                    }
                });
                siteOperations.forEach(function(myField) {
                    var obj = {
                        type: 'menuitem',
                        text: myField,
                        onAction: function() {
                            editor.insertContent(myField);
                        }
                    };
                    items2.push(obj);
                }),
                editor.ui.registry.addMenuButton('mybutton2', {
                    text: 'SIP Operations',
                    fetch: function(callback) {
                        callback(items2);
                    }
                });
            }
        });
    </script>
@endpush
