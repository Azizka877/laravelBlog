<div class="card property-card h-100">
    <div class="position-relative">
        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center text-white">
            <i class="fas fa-home fa-4x"></i>
        </div>
        <div class="position-absolute top-0 end-0 m-3">
            <span class="badge bg-{{ $property->sold ? 'danger' : 'success' }}">
                {{ $property->sold ? 'Vendu' : 'Disponible' }}
            </span>
        </div>
    </div>
    
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ route('property.show', ['slug' => $property->getSlug(), 'property' => $property]) }}" 
               class="text-decoration-none text-dark stretched-link">
                {{ Str::limit($property->title, 50) }}
            </a>
        </h5>
        
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-map-marker-alt text-muted me-2"></i>
            <span class="text-muted">{{ $property->city }} ({{ $property->postal_code }})</span>
        </div>
        
        <div class="row mb-3">
            <div class="col-4 text-center">
                <div class="text-primary fw-bold">{{ $property->surface }}m²</div>
                <small class="text-muted">Surface</small>
            </div>
            <div class="col-4 text-center">
                <div class="text-primary fw-bold">{{ $property->rooms }}</div>
                <small class="text-muted">Pièces</small>
            </div>
            <div class="col-4 text-center">
                <div class="text-primary fw-bold">{{ $property->bedrooms }}</div>
                <small class="text-muted">Chambres</small>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="price-tag fw-bold fs-4">
                {{ number_format($property->price, 0, ',', ' ') }} €
            </div>
            <span class="badge bg-primary rounded-pill">
                Étage {{ $property->floor }}
            </span>
        </div>
    </div>
    
    <div class="card-footer bg-transparent border-top-0">
        <div class="d-grid">
            <a href="{{ route('property.show', ['slug' => $property->getSlug(), 'property' => $property]) }}" 
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye me-1"></i>Voir détails
            </a>
        </div>
    </div>
</div>