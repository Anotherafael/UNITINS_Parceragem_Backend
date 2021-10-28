<?php


namespace App\Repositories\Transaction;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\TransactionDeniedException;
use PHPUnit\Framework\InvalidDataProviderException;

class OrderRepository
{

    public function create(array $fields, $token)
    {

        if (!$this->modelCanCreateOrder($token)) {
            throw new TransactionDeniedException('User is not allowed to create orders', 401);
        }

        try {
            DB::beginTransaction();
            $user = Professional::find($token->tokenable_id);
            $fields['date'] = Carbon::parse($fields['date']);
            $fields['id'] = Str::uuid();
            $fields['professional_id'] = $user->id;
            Order::create($fields);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['message' => 'SQL Transaction Error'], 500);
        }
    }

    public function modelCanCreateOrder($token): bool
    {
        if ($token->tokenable_type == 'App\Models\Auth\User') {
            return false;
        } else if ($token->tokenable_type == 'App\Models\Auth\Professional') {
            return true;
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }
    }
}
