<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'content',

        'email',
        'email2',
        'address',

        'contact1',
        'contact2',

        'facebook',
        'facebook_icon',

        'instagram',
        'instagram_icon',

        'twitter',
        'twitter_icon',

        'linkedin',
        'linkedin_icon',

        'youtube',
        'youtube_icon',

        'map_location_link',
        'share_app_link'
    ];
}
