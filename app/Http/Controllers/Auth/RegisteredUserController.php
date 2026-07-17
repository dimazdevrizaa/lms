<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'string', 'in:guru,siswa'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'identifier' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->role = $request->role;
        $user->save();

        if ($request->role === 'siswa') {
            // Check unique NIS
            $request->validate([
                'identifier' => ['unique:students,nis'],
            ]);
            // ponytail: auto register to 'Kelas Digital' with minimal DB queries
            $kelasDigital = \App\Models\SchoolClass::firstOrCreate(
                ['name' => 'Kelas Digital'],
                [
                    'level' => 'X',
                    'academic_year_id' => \App\Models\AcademicYear::where('is_active', true)->first()?->id,
                ]
            );
            Student::create([
                'user_id' => $user->id,
                'nis' => $request->identifier,
                'class_id' => $kelasDigital->id,
            ]);
        } else {
            // Check unique NIP
            $request->validate([
                'identifier' => ['unique:teachers,nip'],
            ]);
            Teacher::create([
                'user_id' => $user->id,
                'nip' => $request->identifier,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        $redirectRoute = match ($user->role) {
            'guru' => 'guru.dashboard',
            'siswa' => 'siswa.dashboard',
            default => 'login',
        };

        return redirect()->route($redirectRoute);
    }
}
