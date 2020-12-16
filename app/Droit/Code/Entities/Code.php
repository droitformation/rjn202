<?php namespace App\Droit\Code\Entities;

use Illuminate\Database\Eloquent\Model;

class Code extends Model {

    protected $fillable = ['code','valid_at','used','user_id'];
    protected $dates    = ['valid_at'];

    public function scopeYear($query, $year)
    {
        if(isset($year)){
            $query->whereYear('valid_at', $year);
        }
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }
}
