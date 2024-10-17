<x-mail::message>
# Nouvelle demande de contact

Une nouvelle demande de contact a ete fait pour le bien <a href="{{ route('property.show', ['slug'=> $property->getSlug(), 'property'=>$property])}}">
    {{ $property->title }}
</a>


-Nom: {{ $data['firstname'] }}
-Prénom: {{ $data['lastname'] }}
-Email: {{ $data['email'] }}
-Téléphone: {{ $data['phone'] }}
**Message:** 
{{ $data['message'] }}
</x-mail::message>
