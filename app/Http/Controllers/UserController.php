<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\DataTables\UserDataTable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        Gate::authorize('users');
        return $dataTable->render('user.index');
    }

    public function add_get()
    {
        Gate::authorize('users');
        return view('user.add');
    }

    public function add_post()
    {
        Gate::authorize('users');

        $attributes = request()->validate([
            'nilgam_id' => ['nullable', 'integer', Rule::unique('users', 'nilgam_id')],
            'name' => ['required'],
            'last_name' => ['required'],
            'role' => ['required', 'in:admin,user'],
            'active' => ['required', 'in:0,1'],
            'email' => ['nullable', 'email', 'max:50', Rule::unique('users', 'email')],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users', 'mobile')],
            'password' => ['required', 'min:5', 'max:20'],
        ]);

        unset($attributes['password']);

        if (request()->get('password')) {
            $attributes['password'] = bcrypt(request()->get('password'));
        }
        
        $user = User::create($attributes);

        $user->logs()->create([
            'user_id' => auth()->user()->id,
            'action' => 'add',
            'data' => json_encode([
                'user' => [
                    'user' => ['add' => [$user->id]]
                ]
            ])
        ]);

        return redirect('users')->with('success', __('message.user').' '.__('message.created_successfully'));
    }

    public function edit_get($id) {
        Gate::authorize('users');

        $user = User::with('logs')->find($id);

        if ($user) {
            return view('user.edit', compact('user'));
        }

        return redirect('users')->with('error', __('message.user').' '.__('message.not_found'));
    }

    public function edit_post($id)
    {
        Gate::authorize('users');

        request()->merge(['id' => $id]);
        $attributes = request()->validate([
            'id' => ['required', 'exists:users,id'],
            'nilgam_id' => ['nullable', 'integer', Rule::unique('users', 'nilgam_id')->ignore(request()->id)],
            'name' => ['required'],
            'last_name' => ['required'],
            'permission_id' => ['nullable', 'exists:permissions,id'],
            'active' => ['required', 'in:0,1'],
            'email' => ['nullable', 'email', 'max:50', Rule::unique('users', 'email')->ignore(request()->id)],
            'mobile' => ['required', 'regex:/^09\d{9}$/', Rule::unique('users', 'mobile')->ignore(request()->id)],
            'password' => ['nullable', 'min:5', 'max:20'],
        ]);

        $old_user = User::find($id);

        unset($attributes['password']);

        if (request()->password) {
            $attributes['password'] = bcrypt(request()->password);
        }

        foreach ($attributes as $key => $attribute) {
            if ($old_user->$key != $attribute) {
                $old_val = $old_user->$key;
                $new_val = $attribute;
                if ($key == 'password') {
                    $old_val = '***';
                    $new_val = '***';
                } elseif ($key == 'role') {
                    $old_val = __('message.'.$old_val);
                    $new_val = __('message.'.$new_val);
                } elseif ($key == 'active') {
                    $key = 'status';
                    $old_val = __('message.'.($old_val ? 'active' : 'inactive'));
                    $new_val = __('message.'.($new_val ? 'active' : 'inactive'));
                }

                if ($old_val && $new_val) {
                    $log_type = 'edit';
                    $log_value = [$old_val => $new_val];
                } elseif ($old_val) {
                    $log_type = 'remove';
                    $log_value = [$old_val];
                } else {
                    $log_type = 'add';
                    $log_value = [$new_val];
                }

                $log[$key][$key] = [$log_type => $log_value];
            }
        }
        
        User::where('id', $id)->update($attributes);

        if (!empty($log)) {
            $old_user->logs()->create([
                'user_id' => auth()->user()->id,
                'action' => 'edit',
                'data' => json_encode($log)
            ]);
        }

        return redirect('users')->with('success', __('message.user').' '.__('message.updated_successfully'));
    }

    public function login()
    {
        $attributes = request()->validate([
            'mobile' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where('mobile', $attributes['mobile'])->first();
        if ($user && !$user['active']) {
            return back()->withErrors(['mobile' => __('message.your_account_is_not_active')]);
        }

        if(Auth::attempt($attributes))
        {
            session()->regenerate();
            Session::put('sidebar_show', 1);
            return redirect()->intended('/')->with(['success' => __('message.you_have_logged_in_successfuly')]);
        }
        else{
            return back()->withErrors(['mobile' => __('message.mobile_or_password_invalid')]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with(['success' => __('message.you_have_been_logged_out')]);
    }

    public function change_user_status($id, $status = 1)
    {
        Gate::authorize('users');

        $account = User::find($id);
        if ($account) {
            $type = 'success';
            $message = __('message.user').' '.__('message.updated_successfully');
            if ($status == 1 || $status == 0) {
                $account->logs()->create([
                    'user_id' => auth()->user()->id,
                    'action' => 'edit',
                    'data' => json_encode([
                        'status' => [
                            'status' => ['edit' => [__('message.'.($account->active ? 'active' : 'inactive')) => __('message.'.($status ? 'active' : 'inactive'))]]
                        ]
                    ])
                ]);

                $account->active = $status;
                $account->save();
            } else{
                $type = 'error';
                $message = __('message.status').' '.__('message.not_found');
            }
            
        } else {
            $type = 'error';
            $message = __('message.user').' '.__('message.not_found');
        }
        
        return redirect('/users')->with($type, $message);
    }

    public function change_user_permission()
    {
        Gate::authorize('users');
        $attributes = request()->validate([
            'id' => ['required', 'exists:users,id'],
            'permission_id' => ['nullable', 'exists:permissions,id'],
        ]);

        if (User::where('id', request()->id)->update($attributes)) {
            return redirect('/users')->with('success',__('message.permission').' '.__('message.updated_successfully'));
        } else {
            return redirect('/users')->with('error',__('message.permission').' '.__('message.failed'));
        }
    }

    public function delete($id)
    {
        Gate::authorize('users');
        $user = Auth::user();
        if ($user->id == $id) {
            return redirect('/users')->with('error', __('message.failed'));
        } else {
            if (User::where('id',$id)->delete()) {
                return redirect('/users')->with('success', __('message.user').' '.__('message.deleted_successfully'));
            } else {
                return redirect('/users')->with('error', __('message.user').' '.__('message.not_found'));
            }
        }
    }

    public function profile()
    {
        $user = auth()->user();
        return view('profile', ['user' => $user]);
    }

    public function edit_profile(Request $request)
    {
        $attributes = request()->validate([
            'email' => ['nullable', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
            'password' => ['nullable', 'min:5', 'max:20'],
        ]);

        $old_user = Auth::user();

        unset($attributes['password']);

        if (request()->password) {
            $attributes['password'] = bcrypt(request()->password);
        }

        foreach ($attributes as $key => $attribute) {
            if ($old_user->$key != $attribute) {
                $old_val = $old_user->$key;
                $new_val = $attribute;
                if ($key == 'password') {
                    $old_val = '***';
                    $new_val = '***';
                }

                if ($old_val && $new_val) {
                    $log_type = 'edit';
                    $log_value = [$old_val => $new_val];
                } elseif ($old_val) {
                    $log_type = 'remove';
                    $log_value = [$old_val];
                } else {
                    $log_type = 'add';
                    $log_value = [$new_val];
                }

                $log[$key][$key] = [$log_type => $log_value];
            }
        }
        
        User::where('id', Auth::user()->id)->update($attributes);

        if (!empty($log)) {
            $old_user->logs()->create([
                'user_id' => auth()->user()->id,
                'action' => 'edit',
                'data' => json_encode($log)
            ]);
        }
        
        return redirect('/profile')->with('success',__('message.profile').' '.__('message.updated_successfully'));
    }
}
