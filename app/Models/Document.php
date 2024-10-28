<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_name', 
        'receiver_name', 
        'sender_department_name', 
        'phone'
    ];

    public function documentFiles()
    {
        return $this->hasMany(DocumentFile::class);
    }
}
