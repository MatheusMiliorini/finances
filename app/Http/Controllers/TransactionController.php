<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function list()
    {
        return $this->transactionService->list();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => ['required', 'string', 'exists:accounts,_id'],
            'type' => ['required', 'in:I,E'], // Transfer uses another endpoint
            'amount' => ['required', 'integer', 'min:1'],
            'category' => ['required', 'string'], // TODO change to DB categories
            'date' => ['required', 'date'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        return response()->json($this->transactionService->store($validator->validated()));
    }

    public function delete($id)
    {
        try {
            $this->transactionService->delete($id);
            return response(null, 202);
        } catch (ModelNotFoundException $ex) {
            return response(null, 404);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account' => ['string', 'exists:accounts'],
            'type' => ['in:I,E'], // Transfer uses another endpoint
            'amount' => ['integer', 'min:1'],
            'category' => ['string'], // TODO change to DB categories
            'date' => ['date'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        try {
            return response()->json($this->transactionService->update($id, $validator->validated()));
        } catch (ModelNotFoundException $ex) {
            return response(null, 404);
        }
    }
}
