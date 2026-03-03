<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MonthlyInterestRate extends Model
{
    protected $fillable = ['year','month','rate','source'];

    public static function forMonth(\Carbon\Carbon $date)
    {
        return static::where('year',$date->year)->where('month',$date->month)->first();
    }
}