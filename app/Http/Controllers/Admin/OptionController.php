<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\optionFormRequest;
use App\Http\Requests\Admin\OptionsFormRequest;

class OptionController extends Controller
{
    
    public function index()
    {
      return view('admin.options.index',[
       'options' => Option::paginate(25)
      ]);
    }


    public function create()
    {
        $option = new option();
        return view('admin.options.form', compact('option'));
    }


    public function store(OptionsFormRequest $request)
    {
        $option = Option::create($request->validated());
        return to_route('admin.option.index')->with('success', 'L\'option a ete creer');
    }

   

    public function edit(Option $option)
    {
        return view('admin.options.form',[
            'option' => $option
        ]);
    }

    public function update(OptionsFormRequest $request, Option $option)
    {
        $option->update($request->validated());
        return to_route('admin.option.index')->with('success', 'L\option a ete modifier');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Option $option)
    {
        $option->delete();
        return to_route('admin.option.index')->with('success', 'L\'option a ete Supprimer');
        
    }
}
