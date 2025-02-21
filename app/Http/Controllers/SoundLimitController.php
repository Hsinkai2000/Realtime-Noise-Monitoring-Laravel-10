<?php

namespace App\Http\Controllers;

use App\Models\SoundLimit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SoundLimitController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                $sound_limit_params = $request->only((new SoundLimit)->getFillable());
                $sound_limit_id = SoundLimit::insertGetId($sound_limit_params);
                $sound_limit = SoundLimit::find($sound_limit_id);
                return render_ok(["sound_limit" => $sound_limit]);
            } catch (Exception $e) {
                return render_error($e->getMessage());
            }
        }
    }

    public function index()
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                return render_ok(["sound_limits" => SoundLimit::all()]);
            } catch (Exception $e) {
                return render_error($e->getMessage());
            }
        }
    }

    public function get(Request $request)
    {
        $id = $request->route('id');
        $user = Auth::user();
        $sound_limit = SoundLimit::find($id);

        if (Gate::authorize('viewOnlyGuestProject', [$sound_limit->measurementPoint->project, $user])) {
            if (!$sound_limit) {
                return render_unprocessable_entity("Unable to find sound limit with id " . $id);
            }
            return render_ok(["sound_limit" => $sound_limit]);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                $id = $request->route('id');
                $sound_limit_params = $request->only((new SoundLimit)->getFillable());

                $sound_limit = SoundLimit::find($id);
                if (!$sound_limit) {
                    return render_unprocessable_entity("Unable to find sound limit with id " . $id);
                }

                if (!$sound_limit->update($sound_limit_params)) {
                    throw new Exception("Unable to update sound limit");
                }

                return render_ok(["sound_limit" => $sound_limit]);
            } catch (Exception $e) {
                render_error($e->getMessage());
            }
        }
    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                $id = $request->route('id');
                $sound_limit = SoundLimit::find($id);
                if (!$sound_limit) {
                    return render_unprocessable_entity("Unable to find sound limit with id " . $id);
                }
                if (!$sound_limit->delete()) {
                    throw new Exception("Unable to delete sound limit");
                }
                return render_ok(["sound_limit" => $sound_limit]);
            } catch (Exception $e) {
                return render_error($e->getMessage());
            }
        }
    }
}
