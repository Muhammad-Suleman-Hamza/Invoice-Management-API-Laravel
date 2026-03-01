<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\InvoiceController;

Route::get('/test', function () {
    return view('welcome');
});

Route::post('/register', function (Request $request) {

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'tenant_id' => 'required|integer'
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'tenant_id' => $request->tenant_id,
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $user = [
        'token' => $token,
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'tenant_id' => $user->tenant_id,
    ];

    return response()->json([
        'user' => $user,
        'message' => 'User registered successfully'
    ], 201);
});

Route::post('/login', function (Request $request) {

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
        'token' => $token
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('contracts/{contract}')->group(function () {
        Route::get('invoices', [InvoiceController::class, 'list']);
        Route::post('invoices', [InvoiceController::class, 'store']);
        Route::get('summary', [InvoiceController::class, 'contractSummary']);
    });

    Route::prefix('invoices/{invoice}')->group(function () {
        Route::get('', [InvoiceController::class, 'show']);
        Route::post('/payments', [InvoiceController::class, 'recordPayment']);
    });
});
