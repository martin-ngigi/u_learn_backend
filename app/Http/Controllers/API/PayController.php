<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MyStripeKeys;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Price;
use Stripe\Webhook;
use Stripe\Checkout\Session;
use Stripe\Exception\UnexpectedValueException;
use Stripe\Exception\SignatureVerificationException;

class PayController extends Controller
{
    /**
     * "id=2" , id is the course id
     *  i.e. http://127.0.0.1:8000/api/checkout?id=2 
     */
    public function checkout(Request $request){
        try{
            $user = $request->user();
            $token = $user->token;
            $courseId = $request->id;

            /***
             * Stripe API key
             */
            $apiKeys = MyStripeKeys::where('live_or_sandbox', 'sandbox')->first();
            Stripe::setApiKey($apiKeys->secret_key);

             $courseResult = Course::where('id', $courseId)->first();

             if(empty($courseResult)){
                return response()->json([
                    'code' => 400,
                    'msg' => 'Course does not exist'
                ], 400);
             }
            
             $orderMap = [];
             $orderMap['course_id'] = $courseId;
             $orderMap['user_token'] = $token;
             $orderMap['status'] = 1;

             /**
              * If the order has been placed before / exists
              */
            $orderRes = Order::where($orderMap)->first();

            /// invalid request 
            if(!empty($orderRes)){
                return response()->json([
                    'code' => 200,
                    'msg' => 'You already bought this course.',
                    'data' => $orderRes
                ],400);
            }

            /// New order for the user and let's submit
            $YOUR_DOMAIN = env('APP_URL');
            $map['user_token'] = $token;
            $map['course_id'] = $courseId;
            $map['total_amount'] = $courseResult->price;
            $map['status'] = 0;
            $map['created_at'] = Carbon::now();
            $map['updated_at'] = Carbon::now();

            $orderNum = Order::insertGetId($map);

            // create payment session
            $checkOutSession = Session::create([
                'line_items' =>[[
                    'price_data' =>[
                        'currency' => 'USD',
                        'product_data' =>[
                            'name' => $courseResult->name,
                            'description' => $courseResult->description,
                        ],
                        'unit_amount' => intval(($courseResult->price)*100),
                    ],
                    'quantity' => 1,
                ]],
                'payment_intent_data' => [
                    'metadata' => ['order_num' => $orderNum, 'user_token' => $token],
                ],
                'metadata' => ['order_num' => $orderNum, 'user_token' => $token],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN. 'success',
                'cancel_url' => $YOUR_DOMAIN. 'cancel',
            ]);

            return response()->json([
                'code' => 200,
                'msg' => 'Success',
                'data' => $checkOutSession->url,
            ],200);
        }
        catch(\Throwable $th){
            return response()->json([
                'error' => $th->getMessage(),
            ]);
        }

    }
}
