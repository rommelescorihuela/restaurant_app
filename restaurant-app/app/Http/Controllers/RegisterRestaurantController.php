<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\RestaurantRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterRestaurantController extends Controller
{
    public function show()
    {
        return view('register');
    }

    public function store(Request $request, RestaurantRegistrationService $service)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'subdomain' => 'required|alpha_dash|min:3|max:50|unique:tenants,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $restaurant = $service->register($validated);

        Auth::loginUsingId(
            \App\Models\User::where('email', $validated['email'])->firstOrFail()->id
        );

        return redirect('/app')->with('success', 'Restaurante creado. Bienvenido.');
    }
}
