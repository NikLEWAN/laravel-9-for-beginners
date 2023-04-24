<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'body',
        'image_path',
        'is_published',
        'min_to_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Change table name
    // protected $table = 'posts';

    // Change primary key of table
    // protected $primaryKey = 'title';

    // Non increment primary key
    // protected $incrementing = false;

    // Disable timestamps
    // public $timestamps = false;

    // Change format
    // protected $dateFormat = 'U';

    // Change driver for model
    // protected $connection = 'sqlite';

    // Default values for attributes
    // protected $attributes = [
    //    'is_published' => true
    // ];

}
