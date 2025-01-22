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
            <label for="report_name" class="form-label">{{ __('Report Name') }}</label>
            <input type="text" name="report_name" class="form-control @error('report_name') is-invalid @enderror" value="{{ old('report_name', $report?->report_name) }}" id="report_name" placeholder="Report Name">
            {!! $errors->first('report_name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="report_description" class="form-label">{{ __('Report Description') }}</label>
            <input type="text" name="report_description" class="form-control @error('report_description') is-invalid @enderror" value="{{ old('report_description', $report?->report_description) }}" id="report_description" placeholder="Report Description">
            {!! $errors->first('report_description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="report" class="form-label">{{ __('Report') }}</label>
            <textarea rows="10" id="report" placeholder="Build your report" name="report"
                class="form-control @error('report') is-invalid @enderror">{!! (old('report', $report?->report)) !!}</textarea>
            {!! $errors->first('formula', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <input type="hidden" name="rep_id" value="{{ $report?->id }}">
    <input type="hidden" name="cycle_id" value="{{ $cycle->id }}">
    <input type="hidden" name="created_by" value="{{ \Auth::user()->id }}">

    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
@push('script')
    <script src="/assets/js/tinymce/tinymce.min.js"></script>
    <script>
        var consolidatedVariables = @json($consolidatedVariables ?? '');
        tinymce.init({
            content_css: "/assets/css/report.css?v=",
            // menubar: false,
            selector: 'textarea#report',
            height: 500,
            paste_retain_style_properties: "all",
            paste_as_text: false,
            plugins: ['table wordcount', "advlist autolink lists link image charmap print preview hr anchor pagebreak", "searchreplace wordcount visualblocks visualchars code fullscreen", "insertdatetime media nonbreaking save table contextmenu directionality", "emoticons template paste textcolor  "],
            // toolbar: 'mybutton additem',
            content_style: '.left { text-align: left; } ' + 'img.left { float: left; } ' + 'table.left { float: left; } ' + '.right { text-align: right; } ' + 'img.right { float: right; } ' + 'table.right { float: right; } ' + '.center { text-align: center; } ' + 'img.center { display: block; margin: 0 auto; } ' + 'table.center { display: block; margin: 0 auto; } ' + '.full { text-align: justify; } ' + 'img.full { display: block; margin: 0 auto; } ' + 'table.full { display: block; margin: 0 auto; } ' + '.bold { font-weight: bold; } ' + '.italic { font-style: italic; } ' + '.underline { text-decoration: underline; } ' + '.example1 {} ' + 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }' + '.tablerow1 { background-color: #D3D3D3; }',
            toolbar1: " undo redo | styleselect | bold italic | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
            toolbar2: "print preview | forecolor backcolor emoticons | mybutton ",
            setup: function(editor) {
                var items = [];
                consolidatedVariables.forEach(function(myField) {
                    var obj = {
                        type: 'menuitem',
                        text: myField,
                        onAction: function() {
                            editor.insertContent(myField);
                        }
                    };
                    items.push(obj);
                }),
                editor.ui.registry.addMenuButton('mybutton', {
                    text: 'Consolidated Variables',
                    fetch: function(callback) {
                        callback(items);
                    }
                });
            }
            });
    </script>
@endpush
