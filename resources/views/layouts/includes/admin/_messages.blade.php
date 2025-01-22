@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('message'))
    <h2 class="alert alert-success">{{ session('message') }},</h2>
@endif
@if (session('error-message'))
    <h2 class="alert alert-warning">{{ session('error-message') }},</h2>
@endif
