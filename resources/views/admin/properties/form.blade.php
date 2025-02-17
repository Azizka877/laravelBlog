@extends('admin.admin')
@section('title', $property->exists ?'Editer un bien': 'Creer  un bien')
@section('content')
<h1>@yield('title')</h1>
<form class="vstack gap-2" action="{{route($property->exists?'admin.property.update': 'admin.property.store',$property)}}" method="post">
    @csrf
    @method($property->exists? 'put': 'post')
    <div class="row">
        @include('shared.input',['class'=> 'col', 'name'=>'title', 'label'=>'Titre','value'=>$property->title])
        <div class="col row">
          @include('shared.input',['class'=> 'col','name'=>'surface', 'label'=>'Surface','value'=>$property->surface])
          @include('shared.input',['class'=> 'col','name'=>'price', 'label'=>'Prix','value'=>$property->price])
        </div>
    </div>
    @include('shared.input',['type'=>'textarea','name'=>'description', 'label'=>'description','value'=>$property->description])
    <div class="row">
        @include('shared.input',['class'=> 'col','name'=>'rooms', 'label'=>'Pieces','value'=>$property->rooms])
        @include('shared.input',['class'=> 'col','name'=>'bedrooms', 'label'=>'Chambres','value'=>$property->bedrooms])
        @include('shared.input',['class'=> 'col','name'=>'floor' , 'label'=>'Etages','value'=>$property->floor])        
    </div>
    <div class="row">
        @include('shared.input',['class'=> 'col','name'=>'city', 'label'=>'Ville','value'=>$property->city])
        @include('shared.input',['class'=> 'col','name'=>'address', 'label'=>'Adresse','value'=>$property->address])
        @include('shared.input',['class'=> 'col','name'=>'postal_code' , 'label'=>'Code postal','value'=>$property->postal_code])        
    </div>
    @include('shared.select',['name'=>'options' , 'label'=>'Options','value'=>$property->options()->pluck('id'), 'multiple'=>true])        
    @include('shared.checkbox',['name'=>'sold' , 'label'=>'Vendu','value'=>$property->sold,'options'=> $options])        
     
    <div>
        <button class="btn btn-primary">
            @if($property->exists)
                Modifier
            @else
                Créer
            @endif
            bien
        </button>
    </div>
</form>
@endsection