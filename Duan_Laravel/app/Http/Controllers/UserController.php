<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Lang;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $result = $this->userRepository->all($request, $request->input('perPage', 10));
        $users = $result['users'];
        $filters = $result['filters'];

        if ($request->ajax()) {
            return view('users.index', compact('users', 'filters'))
                ->fragment('users-list');
        }

        return view('users.index', compact('users', 'filters'));
    }

    
    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->createUser($request->validated());
        return redirect()->route('users.index')->with('success', trans('users.user_created_success'));
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

        $this->userRepository->updateUser($user, $validatedData);

        return redirect()->route('users.index')->with('success', trans('users.user_updated_success'));
    }

    public function destroy(User $user)
    {
        $this->userRepository->deleteUser($user);

        if (auth()->id() == $user->id) {
            auth()->logout();
            return response()->json(['message' => trans('users.user_delete')], 200);
        }

        if (request()->ajax()) {
            $users = User::where('is_delete', 0)->paginate(10);
            return response()->json([
                'message' => trans('users.deleted_successfully'),
                'total' => $users->total()
            ], 200);
        }

        return response()->json(['message' => trans('users.user_pick_delete')], 200);
    }

    public function updateIsActive(User $user)
    {
        $user = $this->userRepository->toggleUserActive($user);

        $message = $user->is_active ? trans('users.user_unlock_success') : trans('users.user_lock_success');

        if (auth()->id() == $user->id && !$user->is_active) {
            auth()->logout();
            return response()->json(['message' => trans('users.user_lock')], 200);
        }

        return response()->json(['message' => $message, 'is_active' => $user->is_active], 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return back()->with('success', trans('users.import_csv'));
    }

    public function export(Request $request)
    {
        $query = $this->userRepository->getExportQuery($request);
        return Excel::download(new UsersExport($query), 'users.csv');
    }
}
