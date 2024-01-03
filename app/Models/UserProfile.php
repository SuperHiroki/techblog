<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    // テーブル名を指定
    protected $table = 'user_profiles';

    // 変更可能なカラム（マスアサインメント）
    protected $fillable = [
        'user_id',
        'name',
        'public_email',
        'github',
        'website',
        'organization',
        'location',
        'bio',
        'sns1',
        'sns2',
        'sns3',
        'sns4',
        'sns5',
        'sns6'
    ];

    // Userモデルとのリレーション（1対1）。Userインスタンス。
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
