<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{

    public function index()
    {

       $nodes = Node::get();

       return view('nodes.index', ['nodes' => $nodes]);

    }
    public function store(Request $request)
    {

        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'title' => 'required',

        ]);
    
        Node::create($request->all());

        return redirect()->route('nodes.index')
                        ->with('success','Node created successfully.');
    }

    public function destroy($id) {

        Node::find($id)->delete();
        
        return json_encode(array('statusCode'=>200));
       
    }

}