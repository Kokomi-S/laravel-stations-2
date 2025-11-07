<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index() {
        $movies = Movie::all();
        return view('movie', ['movies' => $movies]);
    }

    public function adminIndex() {
        $movies = Movie::all();
        return view('admin.movies.index', ['movies' => $movies]);
    }
}