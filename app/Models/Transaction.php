<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public const INCOME = 'I';
    public const EXPENSE = 'E';
    public const TRANSFER = 'T';

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'category',
        'date',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
