<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $term = trim((string) $request->query('q', ''));

        $users = User::query()
            ->withCount('orders')
            ->when($term !== '', function ($q) use ($term) {
                $like = '%'.addcslashes($term, '%_\\').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $like)->orWhere('email', 'like', $like));
            })
            ->orderBy('id')
            ->paginate(8)
            ->withQueryString();

        return view('admin.users', [
            'users' => $users,
            'search' => $term,
            'counts' => [
                'all' => User::count(),
                'week' => User::where('created_at', '>=', now()->subDays(7))->count(),
                'today' => User::whereDate('created_at', today())->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', ['user' => new User]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge(['email' => Str::lower((string) $request->input('email'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in([User::ROLE_CUSTOMER, User::ROLE_ADMIN])],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        try {
            $user = new User(['name' => $data['name'], 'email' => $data['email'], 'password' => $data['password']]);
            // "role" is not mass assignable, so it is set explicitly after validation.
            $user->role = $data['role'];
            $user->save();
        } catch (Throwable $e) {
            Log::error('User create failed', ['exception' => $e]);

            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'The account could not be created. Please try again.']);
        }

        return redirect()->route('admin.users')->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', ['user' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->merge(['email' => Str::lower((string) $request->input('email'))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in([User::ROLE_CUSTOMER, User::ROLE_ADMIN])],
            // Optional: leave blank to keep the current password.
            'password' => ['nullable', 'string', 'confirmed', Password::defaults()],
        ]);

        // Do not let the last administrator (or yourself) lose admin rights.
        if ($user->isAdmin() && $data['role'] !== User::ROLE_ADMIN
            && ($user->is($request->user()) || $this->isLastAdmin($user))) {
            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['role' => 'This administrator cannot be changed to a customer.']);
        }

        try {
            $user->fill(['name' => $data['name'], 'email' => $data['email']]);
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->role = $data['role'];
            $user->save();
        } catch (Throwable $e) {
            Log::error('User update failed', ['exception' => $e]);

            return back()->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'The account could not be updated. Please try again.']);
        }

        return redirect()->route('admin.users')->with('status', 'User updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        if ($user->isAdmin() && $this->isLastAdmin($user)) {
            return back()->withErrors(['delete' => 'The last administrator cannot be deleted.']);
        }

        // Orders are kept as history, so a user who has orders is not deleted.
        if ($user->orders()->exists()) {
            return back()->withErrors(['delete' => 'This user has orders and cannot be deleted.']);
        }

        try {
            $user->delete();
        } catch (Throwable $e) {
            Log::error('User delete failed', ['exception' => $e]);

            return back()->withErrors(['delete' => 'The user could not be deleted.']);
        }

        return redirect()->route('admin.users')->with('status', 'User deleted.');
    }

    private function isLastAdmin(User $user): bool
    {
        return User::where('role', User::ROLE_ADMIN)->where('id', '!=', $user->id)->doesntExist();
    }
}
