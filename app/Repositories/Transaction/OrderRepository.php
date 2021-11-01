<?php


namespace App\Repositories\Transaction;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Service\Task;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use App\Models\Service\Profession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\TransactionDeniedException;
use PHPUnit\Framework\InvalidDataProviderException;

class OrderRepository
{

    public function getMyOrders($token)
    {

        if (!$this->modelCanCreateOrder($token)) {
            throw new TransactionDeniedException('User does not have orders', 401);
        }

        $user = Professional::find($token->tokenable->id);

        try {
            $orders = Order::select('orders.*')
                ->with('professional', 'task')
                ->where('orders.professional_id', '=', $user->id)
                ->get();
        } catch (Exception $e) {
            dd($e->getMessage());
        }

        return $orders;
    }

    public function create(array $fields, $token)
    {

        if (!$this->modelCanCreateOrder($token)) {
            throw new TransactionDeniedException('User is not allowed to create orders', 401);
        }

        if (!$this->modelHasProfession($fields['task_id'], $token)) {
            throw new TransactionDeniedException('You are not allowed to work this task', 401);
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

    public function modelHasProfession($task_id, $token)
    {
        // Find task profession.
        $task = Task::find($task_id);
        $task_profession = Profession::select('professions.*')
            ->with('professionals', 'tasks')
            ->whereHas('tasks', function ($q) use ($task) {
                $q->where('id', '=', $task->id);
            })
            ->first();

        // Find user professions
        $user = Professional::find($token->tokenable_id);
        $user_professions = Profession::select('professions.*')
            ->with('professionals', 'tasks')
            ->whereHas('professionals', function ($q) use ($user) {
                $q->where('professional_id', '=', $user->id);
            })
            ->get();

        // Find if user professions has the task profession.
        foreach ($user_professions as $profession) {
            if ($profession == $task_profession) {
                return true;
            }
        }

        return false;
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
