<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sub_catagory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'catagory_id',
        'description',
        'image',
        'status',
    ];

    public function catagory()
    {
        return $this->belongsTo(catagory::class);
    }

    public function sub_sub_catagories()
    {
        return $this->hasMany(sub_sub_catagory::class);
    }

    public function documents()
    {
        return $this->hasMany(document::class);
    }





}
