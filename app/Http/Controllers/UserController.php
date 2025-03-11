<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function patch_users(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                $params = $request->json()->all();
                $user_params = $params['users'];
                $project_id = $params['project_id'];
                $original_users = User::where('project_id', $project_id)->get();

                $original_usernames = $original_users->pluck('username')->toArray();
                $new_usernames = array_column($user_params, 'username');
                $removed_usernames = array_diff($original_usernames, $new_usernames);

                if (!empty($removed_usernames)) {
                    User::whereIn('username', $removed_usernames)->delete();
                }

                foreach ($user_params as $user_param) {
                    if (!in_array($user_param['username'], $original_usernames)) {
                        $new_user = [
                            'username' => $user_param['username'],
                            'password' => Hash::make($user_param['password']),
                            'project_id' => $project_id
                        ];
                        User::create($new_user);
                    }
                }

                return render_ok('users patched');
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    public function get_by_project(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                $project_id = $request->route('project_id');
                $user = User::where('project_id', $project_id)->get();
                if (!$user) {

                    return render_unprocessable_entity("Unable to find user with id " . $project_id);
                }
                return render_ok(["users" => $user]);
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    public function index()
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                return render_ok(["users" => User::all()]);
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    public function get(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {

                $id = $request->route('id');
                $user = User::find($id);
                if (!$user) {

                    return render_unprocessable_entity("Unable to find user with id " . $id);
                }
                return render_ok(["user" => $user]);
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                $id = $request->route('id');
                $user_params = $request->only((new User)->getFillable());
                $user = User::find($id);
                if (!$user) {
                    return render_unprocessable_entity("Unable to find user with id " . $id);
                }

                if (!$user->update($user_params)) {
                    throw new Exception("Unable to update user");
                }
                return render_ok(["user" => $user_params]);
            } catch (Exception $e) {
                render_error($e);
            }
        }
    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                $id = $request->route('id');
                $user = User::find($id);
                if (!$user) {
                    return render_unprocessable_entity("Unable to find user with id " . $id);
                }
                if (!$user->delete()) {
                    throw new Exception("Unable to delete user");
                }
                return render_ok(["user" => $user]);
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        // if (Gate::authorize('adminUser', $user)) {
        \Log::info("message");
        $params = $request->all();
        $new_user = [
            'username' => $params['username'],
            'password' => Hash::make($params['password']),
            'project_id' => 1
        ];
        User::create($new_user);
        return $new_user;
        // }
    }

    public function existing_user(Request $request)
    {
        $username = $request->route('username');
        if (User::where('username', $username)->first()) {
            return render_unprocessable_entity('username is already taken');
        }
        return render_ok('user added');
    }
}
