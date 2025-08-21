<?php

namespace App\Http\Controllers;

use App\Jobs\DemoJob;
use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Mail\PropertyContactMail;
use App\Events\ContactRequestEvent;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SearchPropertyRequest;
use App\Http\Requests\PropertyContactRequest;
use App\Notifications\ContactRequestNotification;

class PropertyController extends Controller
{
    public function index(SearchPropertyRequest $request){
     
      
       $query =  Property::query()->orderBy('created_at', 'desc');
        if ($request->filled('price')) {
        $query->where('price', '<=', $request->validated('price'));
    }
      if ($request->filled('surface')) {
        $query->where('surface', '>=', $request->validated('surface')); // >= au lieu de >
    }
    
    if ($request->filled('rooms')) {
        $query->where('rooms', '>=', $request->validated('rooms')); // >= au lieu de >
    }
       if($title=$request->validated('title')){
        $query->where('title', 'like', '%' . $title. '%');
       }
     
     return view('property.index',[
         'properties' => $query->paginate(16),
         'input' => $request->validated()
     ]);
    }
    public function show(string $slug,Property $property){
    
      // /**
      //   * @var User $user */
      //   $user = User::first();
      //   dd($user->notifications);
      DemoJob::dispatch($property);
      $expectedSlug = $property->getSlug();
     if($expectedSlug!== $slug){
       return to_route('property.show',['slug',$expectedSlug, 'property'=>$property]);
     }
     return view('property.show', [
         'property' => $property
     ]);
    }
    public function contact(Property $property,PropertyContactRequest $request){
       /**
        * @var User $user
        */
      //  $user = User::first();
      //  $user->notify(new ContactRequestNotification($property,$request->validated()));
      // event(new ContactRequestEvent($property,$request->validated()));
      // Mail::send(new PropertyContactMail($property,$request->validated()));
      return back()->with('success','Votre Demande de contact a bien ete envoyer');
    }
}

