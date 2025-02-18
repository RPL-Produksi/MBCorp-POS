<div class="col-12">
    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger border-left-danger" role="alert">
            {{ Session::get('error') }}
        </div>
    @endif
</div>
