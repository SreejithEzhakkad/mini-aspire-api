<?php

namespace App\Models;

use App\Models\User;
use App\Models\Repayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'terms',
        'status',
        'requested_date'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:3',
    ];

    /**
     * Get the user that taken the loan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get repayments of the loan.
     */
    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}
