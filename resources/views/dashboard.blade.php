<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
            </h2>
            <div class="btn-group">
                <a href="{{ route('admin.property.index') }}" class="btn btn-primary">
                    <i class="fas fa-building me-1"></i>Gérer les biens
                </a>
                <a href="{{ route('admin.option.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-cog me-1"></i>Options
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="row mb-6">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total des biens</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Property::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-building fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Biens disponibles</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Property::where('sold', false)->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-home fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Biens vendus</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Property::where('sold', true)->count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Options</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Option::count() }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-cog fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h3 class="text-gray-800">Bienvenue sur votre tableau de bord !</h3>
                        <p class="text-muted">Gérez facilement vos propriétés et options immobilières.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-building fa-2x text-primary mb-3"></i>
                                        <h5>Gestion des biens</h5>
                                        <p class="text-muted">Ajoutez, modifiez et gérez vos propriétés</p>
                                        <a href="{{ route('admin.property.index') }}" class="btn btn-outline-primary btn-sm">
                                            Accéder aux biens
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-cog fa-2x text-warning mb-3"></i>
                                        <h5>Gestion des options</h5>
                                        <p class="text-muted">Configurez les options de vos propriétés</p>
                                        <a href="{{ route('admin.option.index') }}" class="btn btn-outline-warning btn-sm">
                                            Accéder aux options
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>