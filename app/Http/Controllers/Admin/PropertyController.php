<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\PropertyFormRequest;
use Illuminate\Auth\Middleware\Authorize;

class PropertyController extends Controller
{
    public function __construct(){
        $this->authorizeResource(Property::class,'property');
      }
    public function index()
    {
      return view('admin.properties.index',[
       'properties' => Property::orderBy('created_at', 'desc')->withTrashed()->paginate(5)
      ]);
    }


    public function create()
    {
        $property = new Property();
        $property->fill([
            'surface'=> 40,
            'city'=> 'Paris',
            'rooms'=> 4,
            'bedrooms'=> 2,
            'floor'=> 0,
            'postal_code'=> 34000,
            'sold'=> false 
        ]);

      

        return view('admin.properties.form',
        [
            'property' => $property,
            'options' => Option::pluck('name', 'id')
        ] );
    }


    public function store(PropertyFormRequest $request)
    {
        
        $property = Property::create($request->validated());
        $property->option()->sync($request->validated('options'));
        
        return to_route('admin.property.index')->with('success', 'Le bien a ete creer');
    }

   

    public function edit(Property $property)
    {  
        $this->Authorize('delete', $property);
        return view('admin.properties.form',[
            'property' => $property,
            'options' => Option::pluck('name', 'id')  // pour les options de la table options ici id est le champs de la table qui sera utilisé pour la relation entre les deux tables.
        ]);
    }

    public function update(PropertyFormRequest $request, Property $property)
    {
        $property->option()->sync($request->validated('options'));
        $property->update($request->validated());
        return to_route('admin.property.index')->with('success', 'Le bien a ete modifier');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();
        return to_route('admin.property.index')->with('success', 'Le bien a ete Supprimer');
        
    }
}
