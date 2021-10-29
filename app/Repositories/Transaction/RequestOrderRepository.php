<?php


namespace App\Repositories\Transaction;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use App\Exceptions\TransactionDeniedException;
use App\Models\Transaction\RequestOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\InvalidDataProviderException;
use App\Traits\ApiResponser;

class RequestOrderRepository
{
    use ApiResponser;

    public function getMyRequestsByUser($token)
    {
        if (!$this->modelCanRequestAnOrder($token)) {
            throw new TransactionDeniedException('Professional is not allowed to request orders', 401);
        }

        $user = User::find($token->tokenable_id);

        try {
            $request = RequestOrder::select('order_requests.*')
            ->with('user', 'order', 'order.task', 'order.professional') 
            ->where('user_id', '=', $user->id)
            ->get();
        } catch (Exception $e) {
            dd($e->getMessage());
        }
        
        return $request;
    }

    public function getMyRequestsByProfessional($token)
    {
        if ($this->modelCanRequestAnOrder($token)) {
            throw new TransactionDeniedException('User is not allowed to has orders', 401);
        }

        $user = Professional::find($token->tokenable_id);

        try {
            $request = RequestOrder::select('order_requests.*')
            ->with('user', 'order', 'order.task', 'order.professional') 
            ->whereHas('order.professional', function ($q) use ($user) {
                $q->where('professional_id', '=', $user->id);
            })
            ->get();
        } catch (Exception $e) {
            dd($e->getMessage());
        }
        
        return $request;
    }

    public function create(array $fields, $token)
    {

        if (!$this->modelCanRequestAnOrder($token)) {
            throw new TransactionDeniedException('Professional is not allowed to request orders', 401);
        }

        try {
            DB::beginTransaction();
            $order = Order::find($fields['order_id']);
            $user = User::find($token->tokenable_id);
            $user->orders()->attach($order);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL transaction', 500);
        }
    }

    public function modelCanRequestAnOrder($token)
    {

        if ($token->tokenable_type == 'App\Models\Auth\User') {
            return true;
        } else if ($token->tokenable_type == 'App\Models\Auth\Professional') {
            return false;
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }
    }
}
