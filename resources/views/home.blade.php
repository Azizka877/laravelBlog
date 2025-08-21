@extends('base')

@section('title', 'MonAgence - Votre agence immobilière de confiance')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Trouvez la propriété de vos rêves</h1>
                <p class="lead mb-4">Découvrez notre sélection exclusive de biens immobiliers soigneusement choisis pour vous.</p>
                <a href="{{ route('property.index') }}" class="btn btn-light btn-lg px-4 py-2">
                    <i class="fas fa-search me-2"></i>Explorer nos biens
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-building fa-10x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="fw-bold">Pourquoi choisir MonAgence ?</h2>
                <p class="text-muted">Notre engagement pour votre satisfaction</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-home fa-2x text-white"></i>
                </div>
                <h4>Large sélection</h4>
                <p class="text-muted">Des centaines de biens soigneusement sélectionnés</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-shield-alt fa-2x text-white"></i>
                </div>
                <h4>Sécurité garantie</h4>
                <p class="text-muted">Transactions sécurisées et accompagnement personnalisé</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-headset fa-2x text-white"></i>
                </div>
                <h4>Support 24/7</h4>
                <p class="text-muted">Une équipe à votre écoute à tout moment</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">Nos derniers biens</h2>
                <p class="text-muted">Découvrez nos propriétés les plus récentes</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('property.index') }}" class="btn btn-outline-primary">
                    Voir tous les biens <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
        
        <div class="row">
            @forelse ($properties as $property)
                <div class="col-lg-4 col-md-6 mb-4">
                    @include('property.card')
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-building fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">Aucun bien disponible pour le moment</h4>
                    <p class="text-muted">Revenez bientôt pour découvrir nos nouvelles propriétés</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Prêt à trouver votre prochain chez-vous ?</h2>
        <p class="lead mb-4">Rejoignez des milliers de clients satisfaits</p>
        <a href="{{ route('property.index') }}" class="btn btn-light btn-lg px-5">
            Commencer maintenant <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</section>
@endsection