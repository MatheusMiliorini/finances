<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function list()
    {
        return $this->accountService->list();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'startBalance' => ['required', 'integer'],
            'currentBalance' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        return response()->json($this->accountService->store($validator->validated()));
    }

    public function delete($id)
    {
        try {
            $this->accountService->delete($id);
            return response(null, 202);
        } catch (ModelNotFoundException $ex) {
            return response(null, 404);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['string', 'max:255'],
            'startBalance' => ['integer'],
            'currentBalance' => ['integer'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        try {
            return response()->json($this->accountService->update($id, $validator->validated()));
        } catch (ModelNotFoundException $ex) {
            return response(null, 404);
        }
    }
}
