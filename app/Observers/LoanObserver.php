<?php

namespace App\Observers;

use App\Models\Loan;
use Illuminate\Support\Carbon;

class LoanObserver
{
    /**
     * Handle the Loan "created" event.
     *
     * @param  \App\Models\Loan  $loan
     * @return void
     */
    public function created(Loan $loan)
    {
        $repaymentAmount = $loan->amount / $loan->terms;
        $repaymentTerms = $loan->terms;
        $repaymentDate = $loan->requestedDate;
        $repayments = [];
        while($repaymentTerms >= 1){
            $repaymentDate = Carbon::parse($repaymentDate)->addDays(7);
            $repayments[] = [
                'scheduled_date' => $repaymentDate,
                'amount' => $repaymentAmount
            ];
            $repaymentTerms --;
        }
        $loan->repayments()->createMany($repayments);
    }

    /**
     * Handle the Loan "updated" event.
     *
     * @param  \App\Models\Loan  $loan
     * @return void
     */
    public function updated(Loan $loan)
    {
        //
    }

    /**
     * Handle the Loan "deleted" event.
     *
     * @param  \App\Models\Loan  $loan
     * @return void
     */
    public function deleted(Loan $loan)
    {
        //
    }

    /**
     * Handle the Loan "restored" event.
     *
     * @param  \App\Models\Loan  $loan
     * @return void
     */
    public function restored(Loan $loan)
    {
        //
    }

    /**
     * Handle the Loan "force deleted" event.
     *
     * @param  \App\Models\Loan  $loan
     * @return void
     */
    public function forceDeleted(Loan $loan)
    {
        //
    }
}
