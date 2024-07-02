<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'IBAN',
        'balance',
        'currency',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'account_id');
    }
   /* public function outgoingTransactions()
    {
        return $this->hasMany(AccountTransaction::class, 'outgoing_iban', 'IBAN');
    }

    public function incomingTransactions()
    {
        return $this->hasMany(AccountTransaction::class, 'incoming_iban', 'IBAN');
    }*/
}

