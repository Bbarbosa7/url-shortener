<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Click extends Model
{
    use HasFactory;

    protected $fillable = ['ip'];

    // Informs that a url can have multiple clicks
    public function Url()
    {
        return $this->belongsto(Url::class);
    }
}
