<?php


namespace App\Repositories\Transaction;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use App\Exceptions\TransactionDeniedException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\InvalidDataProviderException;
use App\Traits\ApiResponser;

class RequestOrderRepository
{
    use ApiResponser;

    public function create(array $fields)
    {

        if(!$this->guardCanRequestAnOrder()) {
            throw new TransactionDeniedException('Professional is not allowed to request orders', 401);
        }

        try {
            DB::beginTransaction();
            $order = Order::find($fields['order_id'])->first();
            $user = User::find(Auth::user()->id)->first();
            $user->orders()->attach($order);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL transaction', 500);
        }
    }

    public function guardCanRequestAnOrder() {

        if (Auth::guard('users')->check()) {
            return true;
        } else if (Auth::guard('professionals')->check()) {
            return false;
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }
    }
}
