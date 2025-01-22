@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (Session::get('success'))
    <div class="alert alert-success-dismissible fade show">
        <ul>
            <li>{{ Session::get('success') }}</li>
        </ul>
    </div>
@endif
