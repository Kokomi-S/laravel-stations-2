<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Schedule;
use Illuminate\Validation\ValidationException;

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

    public function scheduleIndex() {
        $movies = Movie::whereHas('schedules')
            ->with(['schedules' => function ($q) {
                $q->orderBy('start_time', 'asc');
            }])
            ->paginate(20);
        $schedules = Schedule::orderBy('start_time', 'asc')->paginate(20);
        return view('admin.schedules.index', ['schedules' => $schedules, 'movies' => $movies]);
    }

    public function scheduleShow($id) {
        $movies = Movie::findOrFail($id);
        $schedules = Schedule::orderBy('start_time', 'asc')->paginate(20);
        return view('admin.schedules.show', ['schedules' => $schedules, 'movies' => $movies]);
    }

    public function scheduleEdit($id) {
        $schedule = Schedule::findOrFail($id);
        $movie = Movie::findOrFail($schedule->movie_id);

        return view('admin.schedules.edit', compact('movie', 'schedule'));
    }

    public function scheduleUpdate(Request $request, $id) {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'movie_id' => ['exists:movies,id'],
            'start_time_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:end_time_date'],
            'start_time_time' => ['required', 'date_format:H:i', 'before:end_time_time',
                function($attribute, $value, $fail) {
                    // 組み立て
                    $startStr = request()->input('start_time_date') . ' ' . request()->input('start_time_time');
                    $endStr   = request()->input('end_time_date')   . ' ' . request()->input('end_time_time');

                    // try/catch で Carbon を安全に扱う
                    try {
                        $start = CarbonImmutable::createFromFormat('Y-m-d H:i', $startStr);
                        $end   = CarbonImmutable::createFromFormat('Y-m-d H:i', $endStr);
                    } catch (\Exception $e) {
                        $fail('開始/終了の日時形式が正しくありません');
                        return;
                    }

                    // 差分チェック
                    if ($start->diffInMinutes($end) < 6) {
                        $fail('開始時刻と終了時刻の差は5分以上にしてください');
                    }
                },
            ],
            'end_time_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_time_date'],
            'end_time_time' => ['required', 'date_format:H:i', 'after:start_time_time', 
                function($attribute, $value, $fail) {
                    // 組み立て
                    $startStr = request()->input('start_time_date') . ' ' . request()->input('start_time_time');
                    $endStr   = request()->input('end_time_date')   . ' ' . request()->input('end_time_time');

                    // try/catch で Carbon を安全に扱う
                    try {
                        $start = CarbonImmutable::createFromFormat('Y-m-d H:i', $startStr);
                        $end   = CarbonImmutable::createFromFormat('Y-m-d H:i', $endStr);
                    } catch (\Exception $e) {
                        $fail('開始/終了の日時形式が正しくありません');
                        return;
                    }

                    // 差分チェック
                    if ($start->diffInMinutes($end) < 6) {
                        $fail('開始時刻と終了時刻の差は5分以上にしてください');
                    }
                },
            ]
        ],[
            'movie_id.exists' => '指定された映画が存在しません',
            'start_time_date.required' => '開始日の入力は必須です',
            'start_time_date.date_format' => '開始日は有効な日付形式で入力してください',
            'start_time_time.before_or_equal' => '開始時間は終了時間より前の時間にしてください',
            'start_time_time.required' => '開始時間の入力は必須です',
            'start_time_time.date_format' => '開始時間は有効な時間形式で入力してください',
            'end_time_date.required' => '終了日の入力は必須です',
            'end_time_date.date_format' => '終了日は有効な日付形式で入力してください',
            'end_time_time.required' => '終了時間の入力は必須です',
            'start_time_date.before' => '終了日時は開始日時より後にしてください',
            'start_time_time.before' => '終了日時は開始日時より後にしてください',
            'end_time_date.after_or_equal' => '開始時間は終了時間より前の時間にしてください',
            'end_time_time.after' => '開始時間は終了時間より前の時間にしてください',
        ]);

        
        $start = Carbon::createFromFormat('Y-m-d H:i', $validated['start_time_date'] . ' ' . $validated['start_time_time']);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $validated['end_time_date']   . ' ' . $validated['end_time_time']);
                        
        $start_time = $start->format('Y-m-d H:i:s');
        $end_time = $end->format('Y-m-d H:i:s');

        $schedule->update([
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
                
        return redirect()->route('movies.scheduleShow', ['id' => $schedule->movie_id])->with('success', 'スケジュールが更新されました');
    }

    public function scheduleCreate($id) {
        $movie = Movie::findOrFail($id);
        return view('admin.schedules.create', compact('movie'));
    }

    public function scheduleStore(Request $request, $id) {
        $movie = Movie::findOrFail($id);

        $validated = $request->validate([
            'movie_id' => ['exists:movies,id'],
            'start_time_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:end_time_date'],
            'start_time_time' => ['required', 'date_format:H:i', 'before:end_time_time', 
                function($attribute, $value, $fail) {
                    // 組み立て
                    $startStr = request()->input('start_time_date') . ' ' . request()->input('start_time_time');
                    $endStr   = request()->input('end_time_date')   . ' ' . request()->input('end_time_time');

                    // try/catch で Carbon を安全に扱う
                    try {
                        $start = CarbonImmutable::createFromFormat('Y-m-d H:i', $startStr);
                        $end   = CarbonImmutable::createFromFormat('Y-m-d H:i', $endStr);
                    } catch (\Exception $e) {
                        $fail('開始/終了の日時形式が正しくありません');
                        return;
                    }

                    // 差分チェック
                    if ($start->diffInMinutes($end) < 6) {
                        $fail('開始時刻と終了時刻の差は5分以上にしてください');
                    }
                },
            ],
            'end_time_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_time_date'],
            'end_time_time' => ['required', 'date_format:H:i', 'after:start_time_time', 
                function($attribute, $value, $fail) {
                    // 組み立て
                    $startStr = request()->input('start_time_date') . ' ' . request()->input('start_time_time');
                    $endStr   = request()->input('end_time_date')   . ' ' . request()->input('end_time_time');

                    // try/catch で Carbon を安全に扱う
                    try {
                        $start = CarbonImmutable::createFromFormat('Y-m-d H:i', $startStr);
                        $end   = CarbonImmutable::createFromFormat('Y-m-d H:i', $endStr);
                    } catch (\Exception $e) {
                        $fail('開始/終了の日時形式が正しくありません');
                        return;
                    }

                    // 差分チェック
                    if ($start->diffInMinutes($end) < 6) {
                        $fail('開始時刻と終了時刻の差は5分以上にしてください');
                    }
                },
            ],
        ],[
            'movie_id.exists' => '指定された映画が存在しません',
            'start_time_date.required' => '開始日の入力は必須です',
            'start_time_date.date_format' => '開始日は有効な日付形式で入力してください',
            'start_time_time.before_or_equal' => '開始時間は終了時間より前の時間にしてください',
            'start_time_time.required' => '開始時間の入力は必須です',
            'start_time_time.date_format' => '開始時間は有効な時間形式で入力してください',
            'end_time_date.required' => '終了日の入力は必須です',
            'end_time_date.date_format' => '終了日は有効な日付形式で入力してください',
            'end_time_time.required' => '終了時間の入力は必須です',
            'start_time_date.before' => '終了日時は開始日時より後にしてください',
            'start_time_time.before' => '終了日時は開始日時より後にしてください',
            'end_time_date.after_or_equal' => '開始時間は終了時間より前の時間にしてください',
            'end_time_time.after' => '開始時間は終了時間より前の時間にしてください',
        ]);
        
        $start = Carbon::createFromFormat('Y-m-d H:i', $validated['start_time_date'] . ' ' . $validated['start_time_time']);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $validated['end_time_date']   . ' ' . $validated['end_time_time']);
    
        $start_time = $start->format('Y-m-d H:i:s');
        $end_time = $end->format('Y-m-d H:i:s');

        Schedule::create([
            'movie_id' => $movie->id,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

        return redirect()->route('movies.scheduleShow', ['id' => $movie->id])->with('success', 'スケジュールが登録されました');
    }

    public function scheduleDestroyById($schedule_id) {
        $schedule = Schedule::findOrFail($schedule_id);
        $movieId = $schedule->movie_id;
        $schedule->delete();

        return redirect()->route('movies.scheduleShow', ['id' => $movieId])->with('success', 'スケジュールが削除されました');
    }
}