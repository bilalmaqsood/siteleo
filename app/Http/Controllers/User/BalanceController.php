<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ListCost;
use Auth;
use Ignited\LaravelOmnipay\Facades\OmnipayFacade as Omnipay;
use App\Models\Payments;



class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('user.balance', ['ads'=>Auth::user()->ads]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $cost = ListCost::find($request->cost);
        if(is_null($cost)) return back()->withErrors(['Cost not found!']);
        $cost = $cost->find($request->cost);
        
        //$formData = array('number' => '4548812049400004', 'expiryMonth' => '12', 'expiryYear' => '2020', 'cvv' => '123');

        $payment = new Payments();
        $payment->order = $order = time();
        $payment->user_id = Auth::user()->id;
        $payment->cost_id = $cost->id;
        $payment->hash = md5($order.":".Auth::user()->id.":".$cost->id);
        $payment->save();

        return redirect()->route('user-balance-pay-redirect', ['hash' => $payment->hash]);
    }
    
    public function pay($hash, Request $request){

        $payment = Payments::where('hash', $hash)->first();

        if($payment->status!=0 && $payment->status!=1) return redirect()->route('user-balance')->withErrors(['Payment not found!']);

        $options = [
            'amount' => $payment->cost->cost, // decimals mandatory
            'transactionId' => $payment->order, // xxxxAAAAAAAA (4 numbers, 8 alphanumerics)
            'currency' => 'EUR',
            'description' => "Replenishment of account for {$payment->cost->cost} EUR",
            'notifyUrl' => route('user-balance-pay-redirect', ['hash' => $hash]),
            'returnUrl' => route('user-balance-pay-redirect', ['hash' => $hash]),
            'cancelUrl' => route('user-balance-pay-redirect', ['hash' => $hash]),
            'card' => [
                'firstName' => Auth::user()->name
            ]
        ];

        $response = Omnipay::purchase($options)->send();

        if($payment->status==1) {
            $response = Omnipay::completePurchase($options)->send();
            $payment->payment_system_response = $request->all();
            $payment->payment_system_response_data = $response->getData();
        }

        if ($response->isSuccessful())
        {
            $payment->status = 2;
            $payment->save();
            $sum_added = ($payment->cost->cost+$payment->cost->cost_bonus);
            $request->user()->fill([
                'balance' => Auth::user()->balance+$sum_added
            ])->save();
            return redirect()->route('user-balance')->with('status', "Your account has been successfully replenished by {$sum_added} EUR!");

        }
        elseif ($response->isRedirect())
        {
            $payment->status = 1;
            $payment->save();
            $response->redirect();
        }
        else
        {
            $payment->status = 3;
            $payment->error_message = $response->getMessage();
            $payment->save();
            return redirect()->route('user-balance')->withErrors(['Payment error: '.$response->getMessage()]);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
