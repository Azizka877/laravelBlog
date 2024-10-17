<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title'| 'Administration')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

</head>
<body>
  
    <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Agence</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          @php
              $route = request()->route()->getName();
          @endphp
          <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
              <a @class(['nav-link', 'active' => str_contains($route, 'property.')])  href="{{ route('admin.property.index')  }}">Gerer les biens</a>
              <a @class(['nav-link', 'active' => str_contains($route, 'option.')])  href=" {{  route('admin.option.index')  }}">Gerer les options</a>
              <div class="ms-auto">
                @auth
                  <ul class="navbar-nav">
                    <li class="nav-item">
                      <form action="{{ route('logout')}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Se déconnecter</button>
                      </form>
                    </li>
                  </ul>
                @endauth
              </div>
            </div>
          </div>
        </div>
      </nav>


<div class="container mt-5">
    @include('shared.flash')
        @yield('content')
 </div>
    <script>
        new TomSelect('select[multiple]',{plugins: {remove_button: {title: 'Supprimer'}}})
    </script>
</body>
</html>