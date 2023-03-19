<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const INCOME = 'I';
    public const EXPENSE = 'E';

    protected $fillable = [
        'name',
        'account_id',
        'type',
        'amount',
        'category',
        'date',
        'other_id', // Used for transferences
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function other()
    {
        return $this->hasOne(self::class, 'other_id');
    }
}
