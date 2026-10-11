<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['platform', 'url', 'title', 'active'])]
class SocialPost extends Model
{
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
