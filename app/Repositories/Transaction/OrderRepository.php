<?php


namespace App\Repositories\Transaction;

use Exception;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use App\Exceptions\OrderException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\InvalidDataProviderException;

class OrderRepository
{
    
    public function create(array $fields)
    {

        if (!$this->guardCanCreateOrder()) {
            throw new OrderException('Function not authorized', 401);
        }
        
        try {
            DB::beginTransaction();
            $fields['id'] = Str::uuid();
            $fields['professional_id'] = Auth::user()->id;
            Order::create($fields);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['errors' => ['main' => 'SQL Transaction Error']], 500);
        }
    }

    public function guardCanCreateOrder(): bool
    {
        if (Auth::guard('users')->check()) {
            return false;
        } else if (Auth::guard('professionals')->check()) {
            return true;
        } else {
            throw new InvalidDataProviderException('Provider Not found', 422);
        }
    }

}
