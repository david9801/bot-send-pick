<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = ['key'];

    public static function findByKey($key)
    {
        return static::where('key', $key)->first();
    }
}