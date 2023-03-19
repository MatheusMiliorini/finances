<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;

class AccountService
{
    public function list()
    {
        return Account::simplePaginate(5);
    }

    public function get($id)
    {
        return Account::findOrFail($id);
    }

    public function store(array $data)
    {
        $account = new Account($data);
        $account->save();
        return $account;
    }

    public function delete($id)
    {
        $account = Account::findOrFail($id);
        return $account->delete();
    }

    public function update($id, array $data)
    {
        $account = Account::findOrFail($id);
        $account->fill($data);
        $account->save();
        return $account;
    }

    public function updateBalance(Transaction $transaction, $isDelete = false)
    {
        switch ($transaction->type) {
            case Transaction::INCOME:
                $transaction->account->currentBalance += ($transaction->amount * ($isDelete ? -1 : 1));
                $transaction->account->save();
                break;
            case Transaction::EXPENSE:
                $transaction->account->currentBalance -= ($transaction->amount * ($isDelete ? -1 : 1));
                $transaction->account->save();
                break;
            case Transaction::TRANSFER:
                // TODO
                break;
        }
        return $transaction->account;
    }
}
