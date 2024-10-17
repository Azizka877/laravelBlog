@if (session('success'))
       <div class="alert alert-success" role="alert">
           {{ session('success') }}
       </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="my-0">
                @foreach ($errors->all() as $error)
                    {{ $error}}
                @endforeach
            </ul>
        </div>
    @endif