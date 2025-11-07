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

    public function create() {
        return view('admin.movies.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:movies,title',
            'image_url' => 'required|url|max:255',
            'published_year' => 'required|integer|min:1800',
            'is_showing' => 'required|boolean',
            'description' => 'string',
        ],[
            'title.required' => 'タイトルを入力してください',
            'title.unique' => 'このタイトルはすでに存在します',
            'image_url.required' => '画像URLを入力してください',
            'image_url.url' => '正しいURL形式で入力してください',
            'published_year.required' => '公開年を入力してください',
            'published_year.integer' => '公開年は整数で入力してください',
            'published_year.min' => '公開年は1800年以降で入力してください',
            'is_showing.required' => '上映中かどうかを選択してください',
            'is_showing.boolean' => '上映中の値が不正です',
            'description.string' => '説明文を入力してください',
        ]);

        Movie::create($validated);

        return redirect('/admin/movies')->with('success', '映画が作成されました');
    }

    public function edit($id) {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', ['movie' => $movie]);
    }

    public function update(Request $request, $id) {
        $movie = Movie::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:movies,title,' . $movie->id,
            'image_url' => 'required|url|max:255',
            'published_year' => 'required|integer|min:1800',
            'is_showing' => 'required|boolean',
            'description' => 'string',
        ],[
            'title.required' => 'タイトルを入力してください',
            'title.unique' => 'このタイトルはすでに存在します',
            'image_url.required' => '画像URLを入力してください',
            'image_url.url' => '正しいURL形式で入力してください',
            'published_year.required' => '公開年を入力してください',
            'published_year.integer' => '公開年は整数で入力してください',
            'published_year.min' => '公開年は1800年以降で入力してください',
            'is_showing.required' => '上映中かどうかを選択してください',
            'is_showing.boolean' => '上映中の値が不正です',
            'description.string' => '説明文を入力してください',
        ]);

        $movie->update($validated);

        return redirect('/admin/movies')->with('success', '映画が更新されました');
    }
}