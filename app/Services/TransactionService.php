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
}
