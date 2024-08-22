<?php

namespace App\Models\UrlShortener;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;
    protected $table = 'shortenered_urls';
    protected $fillable = ['original_url', 'shortened_url'];
    
}
