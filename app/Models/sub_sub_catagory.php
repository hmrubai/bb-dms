<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sub_sub_catagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sub_catagory_id',
        'description',
        'image',
        'status',
    ];

    public function sub_catagory()
    {
        return $this->belongsTo(sub_catagory::class);
    }

    public function documents()
    {
        return $this->hasMany(document::class);
    }

}
