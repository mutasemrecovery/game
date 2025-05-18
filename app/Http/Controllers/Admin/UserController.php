<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Models\Admin;
use App\Models\CardPackage;
use App\Models\Country;
use App\Models\Department;
use App\Models\Representative;
use App\Models\SectionUser;
use App\Models\Wallet;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query(); // Assuming user_type 1 is for users

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`, `phone`)'), 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->paginate(PAGINATION_COUNT);

        $searchQuery = $request->search;

        return view('admin.users.index', compact('data', 'searchQuery',));
    }


    public function create()
    {
        return view('admin.users.create');
    }

    public function export(Request $request)
    {
        return Excel::download(new UsersExport($request->search), 'users.xlsx');
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'name'  => 'required|string|max:255',
                'phone' => 'required|string|unique:users,phone',
            ]);

            $user = new User();
            $user->name = $request->get('name');
            $user->phone = $request->get('phone');           
            if ($request->activate) {
                $user->activate = $request->get('activate');
            }
           
            
            if ($user->save()) {
                return redirect()->route('users.index')->with(['success' => 'User created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }



    public function edit($id)
    {
        if (auth()->user()->can('user-edit')) {
            $data = User::findorFail($id);
            return view('admin.users.edit', compact('data'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        try {
            // Update user fields
            $user->name = $request->get('name');
            $user->phone = $request->get('phone');
            if ($request->activate) {
                $user->activate = $request->get('activate');
            }
    
            // Save the updated user
            if ($user->save()) {
                return redirect()->route('users.index')->with(['success' => 'User updated']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }
    


}
