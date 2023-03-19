<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function list()
    {
        return Transaction::simplePaginate(5);
    }

    public function store(array $data)
    {
        $transaction = new Transaction($data);
        $transaction->save();
        $this->accountService->updateBalance($transaction);
        return $transaction;
    }

    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $this->accountService->updateBalance($transaction, true);
        if ($transaction->other_id) {
            $this->accountService->updateBalance($transaction->other, true);
            $transaction->other->delete();
        }
        return $transaction->delete();
    }

    public function update($id, array $data)
    {
        $transaction = Transaction::findOrFail($id);
        $this->accountService->updateBalance($transaction, true);
        $transaction->fill($data);
        $transaction->save();
        $this->accountService->updateBalance($transaction);
        return $transaction;
    }

    public function transfer(array $data)
    {
        $from = new Transaction([
            'name' => $data['name'],
            'account_id' => $data['from'],
            'type' => Transaction::EXPENSE,
            'amount' => $data['amount'],
            'category' => 'Tranfer',
            'date' => $data['date'],
        ]);
        $from->save();
        $to = new Transaction([
            'name' => $data['name'],
            'account_id' => $data['to'],
            'type' => Transaction::INCOME,
            'amount' => $data['amount'],
            'category' => 'Tranfer',
            'date' => $data['date'],
            'other_id' => $from->_id,
        ]);
        $to->save();
        $from->update(['other_id' => $to->_id]);
        $this->accountService->updateBalance($from);
        $this->accountService->updateBalance($to);
        return compact('from', 'to');
    }
}
