<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramFollowers extends Model
{
    use HasFactory;

    protected $fillable = [
        'instagram_account',
        'full_name',
        'account_id',
        'username',
        'first_name',
        'last_name',
        'gender',
        'is_private',
        'is_verified',
        'latest_story_ts',
        'profile_pic_url',
        'exported',
    ];
}
