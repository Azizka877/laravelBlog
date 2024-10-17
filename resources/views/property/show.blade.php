@extends('base')
@section('title', $property->title)

@section('content')
    <div class="container mt-3">
        <h1>{{ $property->title }}</h1>
        <h2>{{ $property->rooms }} piece-{{ $property->surface }} m2</h2>

        <div class="text-primary fw-bold" style="font-size: 4rem">
            {{ number_format($property->price, thousands_separator: '') }}
        </div>

        <hr>
        <div class="mt-4">
            <h4>{{ __('property.contact_title') }}</h4>
            <form action="{{ route('property.contact',$property)}}" method="POST" class="vstack gap-3">
                @csrf
                @include('shared.flash')
                <div class="row">
                    @include('shared.input', [
                        'class' => 'col',
                        'name' => 'firstname',
                        'label' => 'Prenom',
                        
                    ])
                    @include('shared.input', ['class' => 'col', 'name' => 'lastname', 'label' => 'Nom'])
                </div>
                <div class="row">
                    @include('shared.input', ['class' => 'col', 'name' => 'phone', 'label' => 'Telephone'])
                    @include('shared.input', [
                        'class' => 'col',
                        'type' => 'email',
                        'name' => 'email',
                        'label' => 'Email',
                    ])
                </div>
                @include('shared.input', [
                    'class' => 'col',
                    'type' => 'textarea',
                    'name' => 'message',
                    'label' => 'Votre Message',
                ])
                <div>
                    <button class="btn btn-primary">Nous contactez</button>
                </div>
            </form>
        </div>

        <div class="mt-4">
            <p>{!! nl2br($property->description) !!}</p>
            <div class="row">
              <div class="col-8">
                <h2>Caracteristiques</h2>
                <table class="table table-srtiped">
                    <tr>
                        <td>Surface habitables</td>
                        <td>{{ $property->surface }} m²</td>
                    </tr>
                    <tr>
                        <td> Pieces</td>
                        <td>{{ $property->rooms }} </td>
                    </tr>
                    <tr>
                        <td>Chambres</td>
                        <td>{{ $property->bedrooms }} </td>
                    </tr>
                    <tr>
                        <td>Etages</td>
                        <td>{{ $property->floor ?: 'Rez de Chausse' }} </td>
                    </tr>
                    <tr>
                        <td>Localisation</td>
                        <td>
                            {{ $property->address }} 
                            {{ $property->city }} ({{ $property->postal_code }})
                        </td>
                    </tr>

                </table>
              </div>
              <div class="col-4">
                <h2>Specificites</h2>
                <ul class="list-group">
                    @foreach ($property->options as $option)
                        <li class="list-group-item ">
                            {{ $option->name }}
                        </li>
                    @endforeach
                </ul>
              </div>
            </div>
        </div>
        
    </div>
@endsection
