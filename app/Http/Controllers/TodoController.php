<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Todo;

class TodoController extends Controller
{
    public function index()
    {
        return view('data');
    }
    public function list()
    {
        return Todo::orderBy('id', 'desc')->get();
    }
    public function add()
    {
        $this->validate(request(), [
            'title' => 'required|min:1'
        ]);
        return Todo::create([
            'title' => request('title')
        ]);
    }
    public function remove($id)
    {
        return Todo::where('id', '=', $id)->delete();
    }
    public function removeAll()
    {
        return Todo::where('dune', '=', True)->delete();
    }
    public function changeState($id)
    {
        $this->validate(request(), [
            'dune' => 'required|boolean'
        ]);
        Todo::where('id', '=', $id)->update(['dune' => request('dune') ? 1 : 0]);
        return ['status' => request('dune')];
    }
}
