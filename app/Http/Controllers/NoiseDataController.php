<?php

namespace App\Http\Controllers;

use App\Models\NoiseData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NoiseDataController extends Controller
{
    public function create(Request $request)
    {
        try {
            $noise_data_params = $request->only((new NoiseData)->getFillable());
            $noise_data_id = NoiseData::insertGetId($noise_data_params);
            $noise_data = NoiseData::find($noise_data_id);
            return render_ok(["noise_data" => $noise_data]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function index()
    {

        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                return render_ok(["noise_datas" => NoiseData::all()]);
            } catch (Exception $e) {
                return render_error($e->getMessage());
            }
        }
    }

    public function get(Request $request)
    {

        $id = $request->route('id');
        $noise_data = NoiseData::find($id);
        $user = Auth::user();

        if (Gate::authorize('viewOnlyGuestProject', [$noise_data->measurementPoint->project, $user])) {
            if (!$noise_data) {
                return render_unprocessable_entity("Unable to find Noise data with id " . $id);
            }
            return render_ok(["noise_data" => $noise_data]);
        }
    }

    public function update(Request $request)
    {

        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            $id = $request->route('id');
            $noise_data_params = $request->only((new NoiseData)->getFillable());
            $noise_data = NoiseData::find($id);
            if (!$noise_data) {
                return render_unprocessable_entity("Unable to find Noise data with id " . $id);
            }

            if (!$noise_data->update($noise_data_params)) {
                throw new Exception("Unable to update Noise data");
            }

            return render_ok(["noise_data" => $noise_data]);
        }
    }

    public function delete(Request $request)
    {

        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            $id = $request->route('id');
            $noise_data = NoiseData::find($id);
            if (!$noise_data) {
                return render_unprocessable_entity("Unable to find Noise data with id " . $id);
            }
            if (!$noise_data->delete()) {
                throw new Exception("Unable to delete Noise data");
            }
            return render_ok(["noise_data" => $noise_data]);
        }
    }
}
