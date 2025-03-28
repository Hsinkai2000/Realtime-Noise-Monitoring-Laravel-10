<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use App\Models\MeasurementPoint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ConcentratorController extends Controller
{
    public function create(Request $request)
    {
        $user = Auth::user();
        if (Gate::authorize('adminUser', $user)) {
            $this->handleValidate($request);
            $concentrator_params = $request->only((new Concentrator)->getFillable());

            $concentrator_id = Concentrator::insertGetId($concentrator_params);
            $concentrator = Concentrator::find($concentrator_id);
            $concentrators = Concentrator::all();
            return render_ok(["concentrator" => $concentrator, 'concentrators' => $concentrators]);
        }
    }

    public function index()
    {
        $user = Auth::user();
        if (Gate::authorize('adminUser', $user)) {
            try {
                $concentrators = Concentrator::all();
                foreach ($concentrators as $concentrator) {
                    $concentrator['isAvailable'] = $concentrator->isAvailable();
                }
                return $concentrators;
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    public function show()
    {
        $user = Auth::user();
        if (Gate::authorize('adminUser', $user)) {
            try {
                return view('web.concentrators', ["concentrators" => Concentrator::with("measurementPoint.project")->get()]);
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
                $concentrator = Concentrator::find($id);
                if (!$concentrator) {

                    return render_unprocessable_entity("Unable to find concentrator with id " . $id);
                }
                return render_ok(["concentrator" => $concentrator]);
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (Gate::authorize('adminUser', $user)) {
            $this->handleValidate($request);

            $id = $request->route('id');
            $concentrator_params = $request->only((new Concentrator)->getFillable());

            $concentrator = Concentrator::find($id);
            if (!$concentrator) {
                return render_unprocessable_entity("Unable to find concentrator with id " . $id);
            }


            $concentrator->update($concentrator_params);
            $concentrators = Concentrator::all();
            return render_ok(["concentrator" => $concentrator, 'concentrators' => $concentrators]);
        }
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        if (Gate::authorize('adminUser', $user)) {
            try {
                $id = $request->route('id');
                $concentrator = Concentrator::find($id);
                if (!$concentrator) {
                    return render_unprocessable_entity("Unable to find concentrator with id " . $id);
                }
                if (!$concentrator->delete()) {
                    throw new Exception("Unable to delete concentrator");
                }

                $concentrators = Concentrator::all();
                return render_ok(["concentrator" => $concentrator, 'concentrators' => $concentrators]);
            } catch (Exception $e) {
                return render_error($e);
            }
        }
    }

    private function handleValidate(Request $request)
    {
        return $request->validate([
            'device_id' => ['required', 'string', 'size:14', Rule::unique('concentrators')->ignore($request->id)]
        ]);
    }
}
