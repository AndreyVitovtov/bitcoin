<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Message;
use App\Models\Withdrawal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Withdrawals extends Controller
{
    public function index()
    {
        return view('admin.withdrawals.index', [
            'withdrawals' => Withdrawal::all(),
            'menuItem' => 'withdrawal'
        ]);
    }

    public function paid(Request $request)
    {
        if($this->changeStatus($request, '1')) {
            $user = Withdrawal::find($request['id'])->user;
            $message = new Message();
            $message->send($user->messenger, $user->chat, '{withdrawal_paid}');
        }
        return redirect()->to(route('withdrawal'));
    }

    public function cancel(Request $request)
    {
        if($this->changeStatus($request, '-1')) {
            $user = Withdrawal::find($request['id'])->user;
            $message = new Message();
            $message->send($user->messenger, $user->chat, '{withdrawal_cancel}');
        }
        return redirect()->to(route('withdrawal'));
    }

    private function changeStatus(Request $request, $status): bool
    {
        $request = $request->post();
        try {
            DB::beginTransaction();

            $withdrawal = Withdrawal::find($request['id']);
            $withdrawal->status = $status;
            $withdrawal->save();
            if($status == '-1') {
                $action = new Action();
                $action->users_id = $withdrawal->users_id;
                $action->type = '+';
                $action->amount = $withdrawal->satoshi;
                $action->date_time = date("Y-m-d H:i:s");
                $action->save();
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
