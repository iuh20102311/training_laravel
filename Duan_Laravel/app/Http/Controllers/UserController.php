<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_delete', 0);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('group_role')) {
            $query->where('group_role', $request->group_role);
        }

        $users = $query->orderByDesc('created_at')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    // Lưu trữ người dùng mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Hiển thị chi tiết người dùng
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Hiển thị form chỉnh sửa người dùng
    public function edit(User $user)
    {
        return view('editProduct', compact('user'));
    }

    // Cập nhật thông tin người dùng
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Xóa người dùng
    public function destroy(User $user)
    {
        $user->update(['is_delete' => 1]);
    return redirect()->route('dashboard')->with('success', 'User marked as deleted successfully.');
    }

    public function delete(User $user)
    {
        if ($user->is_active == 1) {
            $user->update(['is_active' => 0]);
            $message = 'User deactivated successfully.';
        } else {
            $user->update(['is_active' => 1]);
            $message = 'User reactivated successfully.';
        }
        return redirect()->route('dashboard')->with('success', $message);
    }
}

