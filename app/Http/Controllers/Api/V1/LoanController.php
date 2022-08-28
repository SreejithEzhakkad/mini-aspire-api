<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Loan;
use App\Models\Repayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;

class LoanController extends BaseController
{    
        
    /**
     * index
     *
     * @param  mixed $request
     * @return void
     */
    public function index(Request $request)
    {
        $response['loans'] = $request->user()->loans()->with('repayments')->get();

        return $this->sendResponse($response, NULL);
    }
    
    /**
     * show
     *
     * @param  mixed $loan
     * @return void
     */
    public function show(Loan $loan)
    {
        $response['loan'] = $loan->with('repayments')->get();

        return $this->sendResponse($response, NULL);
    }
        
    /**
     * loanRequest
     *
     * @param  mixed $request
     * @return void
     */
    public function loanRequest(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), 
            [
                'amount' => 'required|numeric|gt:0',
                'terms'  => 'required|numeric|gt:0'
            ]);

            if($validator->fails()){
                return $this->sendError(__('Validation Error.'), $validator->errors());
            }

            $validated = $request->safe();

            $loan = $request->user()->loans()->create([
                'amount'         => $validated['amount'],
                'terms'          => $validated['terms'],
                'requested_date' => today()
            ]);

            $response = [
                'loan' => Loan::with('repayments')->find($loan->id)
            ];

            return $this->sendResponse($response, __('Your loan request has been sent successfully.'));
            
        } catch (\Throwable $th) {

            return $this->sendError($th->getMessage(), [], 500);
        }
    }
    
    /**
     * approve
     *
     * @param  mixed $loan
     * @return void
     */
    public function approve(Loan $loan)
    {
        $loan->status = 'APPROVED';
        $loan->save();
        $response['loan'] = Loan::with('repayments')->find($loan->id);

        return $this->sendResponse($response,  __('The loan request has been approved successfully.'));
    }
    
    /**
     * repay
     *
     * @param  mixed $loan
     * @param  mixed $repayment
     * @param  mixed $request
     * @return void
     */
    public function repay(Loan $loan, Repayment $repayment, Request $request)
    {
        if($repayment->loan_id != $loan->id){
            abort(404);
        }
        $validator = Validator::make($request->all(), 
        [
            'amount' => ['required', 'numeric', function ($attribute, $value, $fail) use($repayment) {
                    if ($repayment->amount >= $value) {
                        return $fail(__("Repayment amount is $repayment->amount"));
                    }
                }, function ($attribute, $value, $fail) use($repayment) {
                    if ($repayment->status != 'PENDING') {
                        return $fail(__("This repayment was already paid."));
                    }
                }]
        ]);

        if($validator->fails()){
            return $this->sendError(__('Validation Error.'), $validator->errors());
        }

        $validated = $request->safe();

        $repayment->paid_amount = $validated['amount'];
        $repayment->paid_date = today();
        $repayment->status = 'PAID';
        $repayment->save();
        $response['loan'] = $loan->with('repayments')->get()->first();

        return $this->sendResponse($response,  __('Your repayment has been processed successfully.'));
    }
    
}


