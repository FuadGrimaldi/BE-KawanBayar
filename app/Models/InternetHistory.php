<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetHistory extends Model
{
    use HasFactory;
    protected $table = 'internet_histories';
    protected $fillable = [
        'user_id',
        'data_plan_id',
        'transaction_code',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function dataPlan()
    {
        return $this->belongsTo(DataPlan::class);
    }
}
