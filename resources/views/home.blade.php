@extends('base')

@section('content')
<x-alert type='danger'>
    infos
</x-alert>

<div class="bg-light p-5 mb-5 text-center">
    <div class="container">
        <h1>Agence lorem ipsun</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non quam auctor, varius metus ac, dignissim erat. Sed vel libero eget nunc faucibus congue.</p>
    </div>
</div>

<div class="container">
    <h1>Nos derniers biens</h1>
    <div class="row">
        @foreach ($properties as $property)
        <div class="col">
          @include('property.card')
        </div>
        @endforeach
    </div>
</div>
@endsection