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
        if ($request->user()->tokenCan('admin.*')) {
            $response['loans'] = Loan::with('repayments')->get();

        }else{
            $response['loans'] = $request->user()->loans()->with('repayments')->get();
        }

        return $this->sendResponse($response, NULL);
    }
    
    /**
     * show
     *
     * @param  mixed $loan
     * @return void
     */
    public function show(Loan $loan, Request $request)
    {
        if (!$request->user()->tokenCan('admin.*')) {
            if ($request->user()->cannot('view', $loan)) {
                abort(403);
            }
        }
        
        $response['loan'] = $loan->with('repayments')->first();

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

            $validated = $validator->validated();

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
        if($loan->status == 'APPROVED')
        {
            return $this->sendError(__('This loan is already approved.'), [],403);
        }

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
        if ($request->user()->cannot('update', $loan)) {
            abort(403);
        }

        if($repayment->loan_id != $loan->id){
            abort(404);
        }
        if($loan->status != 'APPROVED')
        {
            return $this->sendError(__('This loan is not approved yet.'), [],403);
        }
        if($repayment->status != 'PENDING')
        {
            return $this->sendError(__('This repayment was already paid.'), [],403);
        }

        $validator = Validator::make($request->all(), 
        [
            'amount' => ['required', 'numeric', function ($attribute, $value, $fail) use($repayment) {
                    if ($repayment->amount >= $value) {
                        return $fail(__("Repayment amount is $repayment->amount"));
                    }
                }]
        ]);

        if($validator->fails()){
            return $this->sendError(__('Validation Error.'), $validator->errors());
        }

        $validated = $validator->validated();

        $repayment->paid_amount = $validated['amount'];
        $repayment->paid_date = today();
        $repayment->status = 'PAID';
        $repayment->save();
        $response['loan'] = $loan->with('repayments')->get()->first();

        return $this->sendResponse($response,  __('Your repayment has been processed successfully.'));
    }
    
}


