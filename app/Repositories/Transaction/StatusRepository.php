<?php


namespace App\Repositories\Transaction;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use App\Exceptions\OrderException;
use App\Exceptions\TransactionDeniedException;
use App\Models\Transaction\RequestOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\InvalidDataProviderException;

class StatusRepository
{
    
    public function handleAccept(array $fields)
    {

        if(!$this->guardCanAcceptOrRejectAnOrder()) {
            throw new TransactionDeniedException('User is not allowed to accept/reject orders', 401);
        }

        try {
            DB::beginTransaction();

            // Status on RequestOrder model is gonna be Accepted
            $request_order = $this->getRequestOrder($fields['request_order_id']);
            $request_order->status = 2;
            $request_order->save();
            
            // Status on Order model is gonna be Accepted
            $order = Order::find($request_order->order->id);
            $order->status = 2;
            $order->save();

            // Others RequestOrder models is gonna be rejected. 'Cause the order is already accepted.
            $list_request = RequestOrder::where('order_id', '=', $order->id)
            ->where('status', '=', 1)
            ->get();

            foreach($list_request as $request) {
                $request->status = 3;
                $request->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL transaction', 500);
        }
    }

    public function handleReject(array $fields)
    {

        if(!$this->guardCanAcceptOrRejectAnOrder()) {
            throw new TransactionDeniedException('User is not allowed to accept/reject orders', 401);
        }

        try {
            DB::beginTransaction();

            // Status on RequestOrder model is gonna be Rejected
            $request_order = $this->getRequestOrder($fields['request_order_id']);
            $request_order->status = 3;
            $request_order->save();

            // Others RequestOrder models will be the same. 'Cause the Order is still pending.
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL transaction', 500);
        }
    }

    public function getRequestOrder($id) : RequestOrder {
        return RequestOrder::find($id);
    }

    public function guardCanAcceptOrRejectAnOrder() {

        if (Auth::guard('users')->check()) {
            return false;
        } else if (Auth::guard('professionals')->check()) {
            return true;
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }
    }
}
