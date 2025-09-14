<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $fillable = ['user_id','drive_file_id','drive_file_name','size_bytes'];
    public $timestamps = true;
}
