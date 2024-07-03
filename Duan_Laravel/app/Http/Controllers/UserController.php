<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_delete', 0);

        $filters = [
            'is_active' => $request->is_active,
            'name' => $request->name,
            'email' => $request->email,
            'group_role' => $request->group_role,
        ];

        if ($filters['name']) {
            $query->where('name', 'like', $filters['name'] . '%');
        }

        if ($filters['email']) {
            $query->where('email', 'like', $filters['email'] . '%');
        }

        if ($filters['is_active']) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($request->filled('group_role')) {
            $query->where('group_role', $filters['group_role']);
        }

        $perPage = $request->input('perPage', 10);

        $users = $query->orderByDesc('created_at')->paginate($perPage)->appends($filters);

        return view('users.index', compact('users', 'filters'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();
        $validatedData['group_role'] = ucfirst(strtolower($validatedData['group_role']));

        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'Thông tin người dùng đã được cập nhật thành công.');
    }

    public function destroy(User $user)
    {
        $user->update(['is_delete' => 1]);
        if (auth()->id() == $user->id) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị xóa. Liên hệ admin để làm rõ.');
        }
        return redirect()->route('users.index')->with('success', 'Người dùng đã được đánh dấu là đã xóa.');
    }

    public function update_is_active(User $user)
    {
        if ($user->is_active == 1) {
            $user->update(['is_active' => 0]);
            $message = 'Người dùng đã bị khóa thành công.';
        } else {
            $user->update(['is_active' => 1]);
            $message = 'Người dùng đã được mở khóa thành công.';
        }

        if (auth()->id() == $user->id && $user->is_active == 0) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa.');
        }

        return redirect()->route('users.index')->with('success', $message);
    }
}

