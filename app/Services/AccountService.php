<?php

namespace App\Services;

use App\Models\Account;

class AccountService
{
    public function list()
    {
        return Account::simplePaginate(5);
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
}
