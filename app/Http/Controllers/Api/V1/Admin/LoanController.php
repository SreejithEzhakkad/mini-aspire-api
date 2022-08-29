<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class LoanController extends BaseController
{    
        
    /**
     * List all loans
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {
        $response['loans'] = Loan::with('repayments')->get();
        
        return $this->sendResponse($response, NULL);
    }
    
    /**
     * show a loan
     *
     * @param  mixed $loan
     * @return void
     */
    public function show(Loan $loan, Request $request)
    {
        $response['loan'] = $loan->with('repayments')->first();

        return $this->sendResponse($response, NULL);
    }
        
    
    /**
     * Change the loan status as  `APPROVED`
     *
     * @param  mixed $loan
     * @return void
     */
    public function approve(Loan $loan)
    {
        if($loan->status == 'APPROVED')
        {
            return $this->sendError(__('This loan is already approved.'), [],403);
        }

        $loan->status = 'APPROVED';
        $loan->save();
        $response['loan'] = Loan::with('repayments')->find($loan->id);

        return $this->sendResponse($response,  __('The loan request has been approved successfully.'));
    }
}


