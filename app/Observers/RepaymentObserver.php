<?php

namespace App\Observers;

use App\Models\Repayment;

class RepaymentObserver
{
    /**
     * Handle the Repayment "created" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function created(Repayment $repayment)
    {
        //
    }

    /**
     * Handle the Repayment "updated" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function updated(Repayment $repayment)
    {
        if($repayment->status == 'PAID' ){
            $pandingPayments = Repayment::where('status','PENDING')->where('loan_id',$repayment->loan_id)->count();
            if($pandingPayments === 0){
                $repayment->loan()->update(['status' => 'PAID']);
            }
        }
    }

    /**
     * Handle the Repayment "deleted" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function deleted(Repayment $repayment)
    {
        //
    }

    /**
     * Handle the Repayment "restored" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function restored(Repayment $repayment)
    {
        //
    }

    /**
     * Handle the Repayment "force deleted" event.
     *
     * @param  \App\Models\Repayment  $repayment
     * @return void
     */
    public function forceDeleted(Repayment $repayment)
    {
        //
    }
}
