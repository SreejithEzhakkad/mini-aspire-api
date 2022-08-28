<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Repayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scheduled_date',
        'amount',
        'paid_date',
        'paid_amount',
        'status',
    ];

    /**
     * Get the loan that belongs to the repayment.
     */
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
