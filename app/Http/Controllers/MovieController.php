<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Schedule;

class MovieController extends Controller
{
    public function index(Request $request) {
        $query = Movie::query();

        // 上映状況での絞り込み
        if ($request->has('is_showing') && $request->is_showing === '1') {
            $query->where('is_showing', true);
        } else if ($request->has('is_showing') && $request->is_showing === '0') {
            $query->where('is_showing', false);
        }
        // キーワード検索（タイトルと概要の両方から検索）
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                ->orWhere('description', 'like', '%' . $keyword . '%')
                ->orWhereHas('genre', function($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            });
        }

        $movies = $query->paginate(20); // 1ページあたり20件でページネーション
        $movies->appends($request->query()); // クエリパラメータをページネーションリンクに追加
        return view('admin.movies.index', ['movies' => $movies]);
    }

    public function adminIndex() {
        $movies = Movie::paginate(20);
        return view('admin.movies.index', ['movies' => $movies]);
    }

    public function create() {
        return view('admin.movies.create');
    }

    public function store(Request $request) {
        
        $validated = $request->validate([
            'title' => 'required|string|unique:movies,title',
            'genre' => 'required|string|max:255',
            'image_url' => 'required|url|max:255',
            'published_year' => 'required|integer|min:1800',
            'is_showing' => 'required|boolean',
            'description' => 'string',
        ],[
            'title.required' => 'タイトルを入力してください',
            'title.unique' => 'このタイトルはすでに存在します',
            'genre.required' => 'ジャンルを入力してください',
            'image_url.required' => '画像URLを入力してください',
            'image_url.url' => '正しいURL形式で入力してください',
            'published_year.required' => '公開年を入力してください',
            'published_year.integer' => '公開年は整数で入力してください',
            'published_year.min' => '公開年は1800年以降で入力してください',
            'is_showing.required' => '上映中かどうかを選択してください',
            'is_showing.boolean' => '上映中の値が不正です',
            'description.string' => '説明文を入力してください',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // ジャンルが既に存在するかチェック、なければ新規作成
            $genre = Genre::firstOrCreate(['name' => $validated['genre']]);
            $validated['genre_id'] = $genre->id;
            unset($validated['genre']);
            Movie::create($validated);
        });
        
        return redirect('/admin/movies')->with('success', '映画が作成されました');
    }

    public function edit($id) {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', ['movie' => $movie]);
    }

    public function update(Request $request, $id) {
        $movie = Movie::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|unique:movies,title,' . $movie->id,
            'genre' => 'required|string|max:255',
            'image_url' => 'required|url|max:255',
            'published_year' => 'required|integer|min:1800',
            'is_showing' => 'required|boolean',
            'description' => 'string',
        ],[
            'title.required' => 'タイトルを入力してください',
            'title.unique' => 'このタイトルはすでに存在します',
            'genre.required' => 'ジャンルを入力してください',
            'image_url.required' => '画像URLを入力してください',
            'image_url.url' => '正しいURL形式で入力してください',
            'published_year.required' => '公開年を入力してください',
            'published_year.integer' => '公開年は整数で入力してください',
            'published_year.min' => '公開年は1800年以降で入力してください',
            'is_showing.required' => '上映中かどうかを選択してください',
            'is_showing.boolean' => '上映中の値が不正です',
            'description.string' => '説明文を入力してください',
        ]);

        DB::transaction(function() use ($validated, $movie){
            $genre = Genre::firstOrCreate(['name' => $validated['genre']]);
            $validated['genre_id'] = $genre->id;
            unset($validated['genre']);
            $movie->update($validated);
        });
        return redirect('/admin/movies')->with('success', '映画が更新されました');
    }

    public function destroy($id) {
        $movie = Movie::findOrFail($id);
        $movie->delete();

        return redirect('/admin/movies')->with('success', '映画が削除されました');
    }

    // 一般ユーザー向けの詳細表示（スケジュール）
    public function show($id) {
        $movie = Movie::findOrFail($id);
        $schedules = Schedule::where('movie_id', $id)->orderBy('start_time', 'asc')->get();

        return view('movieSchedule', compact('movie', 'schedules'));
    }
}
