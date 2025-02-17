@extends('admin.admin')
@section('title', $option->exists ?'Editer une option': 'Creer  une option')
@section('content')
<h1>@yield('title')</h1>
<form class="vstack gap-2" action="{{route($option->exists?'admin.option.update': 'admin.option.store',$option)}}" method="post">
    @csrf
    @method($option->exists? 'put': 'post')
    
    @include('shared.input',['name'=>'name', 'label'=>'name','value'=>$option->name])
         
    <div>
        <button class="btn btn-primary">
            @if($option->exists)
                Modifier
            @else
                Créer
            @endif
            bien
        </button>
    </div>
</form>
@endsection