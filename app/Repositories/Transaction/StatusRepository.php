<?php


namespace App\Repositories\Transaction;

use Exception;
use App\Models\Transaction\Order;
use App\Exceptions\TransactionDeniedException;
use App\Models\Transaction\RequestOrder;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\InvalidDataProviderException;

class StatusRepository
{
    
    public function handleAccept($token, $field)
    {

        if(!$this->modelCanAcceptOrRejectAnOrder($token)) {
            throw new TransactionDeniedException('User is not allowed to accept/reject orders', 401);
        }

        try {
            DB::beginTransaction();

            // Status on RequestOrder model is gonna be Accepted
            $request_order = $this->getRequestOrder($field);
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

    public function handleReject($token, $field)
    {

        if(!$this->modelCanAcceptOrRejectAnOrder($token)) {
            throw new TransactionDeniedException('User is not allowed to accept/reject orders', 401);
        }

        try {
            DB::beginTransaction();

            // Status on RequestOrder model is gonna be Rejected
            $request_order = $this->getRequestOrder($field);
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

    public function modelCanAcceptOrRejectAnOrder($token) {
        if ($token->tokenable_type == 'App\Models\Auth\User') {
            return false;
        } else if ($token->tokenable_type == 'App\Models\Auth\Professional') {
            return true;
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }
    }
}
