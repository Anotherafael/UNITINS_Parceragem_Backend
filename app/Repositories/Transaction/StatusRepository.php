<?php


namespace App\Repositories\Transaction;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use App\Exceptions\OrderException;
use App\Models\Transaction\RequestOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\InvalidDataProviderException;

class StatusRepository
{
    
    public function handleAccept(array $fields)
    {
        try {
            DB::beginTransaction();

            $request_order = $this->getRequestOrder($fields['request_order_id']);
            $request_order->status = 2;
            $request_order->save();

            $order = Order::find($request_order->order->id);
            $order->status = 2;
            $order->save();

            $list_request = RequestOrder::where('order_id', '=', $order->id)->where('status', '=', 1)->get();
            
            for($i = 0; $i < $list_request->count();$i++)
            {
                $list_request[$i]->status = 3;
                $list_request[$i]->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['message' => 'SQL Transaction Error'], 500);
        }
    }

    public function handleReject(array $fields)
    {
        try {
            DB::beginTransaction();
            $request_order = $this->getRequestOrder($fields['request_order_id']);
            $request_order->status = 3;
            $request_order->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['message' => 'SQL Transaction Error'], 500);
        }
    }

    public function getRequestOrder($id) : RequestOrder {
        return RequestOrder::find($id);
    }
}
