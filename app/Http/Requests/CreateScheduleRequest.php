<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\CarbonImmutable;

class CreateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // ルートパラメータから movie_id を取る場合もあるので柔軟に
            'movie_id' => ['sometimes', 'exists:movies,id'],
            'start_time_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:end_time_date'],
            'end_time_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_time_date'],
            'start_time_time' => ['required', 'date_format:H:i','before:end_time_time',
                function($attribute, $value, $fail) {
                    $startStr = $this->input('start_time_date') . ' ' . $this->input('start_time_time');
                    $endStr   = $this->input('end_time_date')   . ' ' . $this->input('end_time_time');

                    $this->validateInterval($startStr, $endStr, $fail);
                },
            ],
            'end_time_time' => ['required', 'date_format:H:i', 'after:start_time_time',
                function($attribute, $value, $fail) {
                    $startStr = $this->input('start_time_date') . ' ' . $this->input('start_time_time');
                    $endStr   = $this->input('end_time_date')   . ' ' . $this->input('end_time_time');

                    $this->validateInterval($startStr, $endStr, $fail);
                },
            ],
        ];
    }
    public function messages(): array
    {
        return [
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
        ];
    }

    // 日時差分チェック
     private function validateInterval(string $startStr, string $endStr, $fail): void
    {
        try {
            $start = CarbonImmutable::createFromFormat('Y-m-d H:i', $startStr);
            $end   = CarbonImmutable::createFromFormat('Y-m-d H:i', $endStr);
        } catch (\Exception $e) {
            $fail('開始/終了の日時形式が正しくありません');
            return;
        }

        if ($start->diffInMinutes($end) < 6) {
            $fail('開始時刻と終了時刻の差は5分以上にしてください');
            return;
        }
    }
}
