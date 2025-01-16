<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use App\Models\MeasurementPoint;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConcentratorController extends Controller
{
    public function create(Request $request)
    {
        $this->handleValidate($request);
        $concentrator_params = $request->only((new Concentrator)->getFillable());

        $concentrator_id = Concentrator::insertGetId($concentrator_params);
        $concentrator = Concentrator::find($concentrator_id);
        $concentrators = Concentrator::all();
        return render_ok(["concentrator" => $concentrator, 'concentrators' => $concentrators]);
    }

    public function index()
    {
        try {
            \Log::info("message1");
            $concentrators = Concentrator::with("measurementPoint.project")->get();
            \Log::info("message2");
            foreach ($concentrators as $concentrator) {
                if ($concentrator->measurementPoint) {
                    $concentrator['available'] = 0;
                } else {
                    $concentrator['available'] = 1;
                }
            }
            \Log::info("message4");
            \Log::info($concentrators);

            return ["concentrators" => $concentrators];
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function show()
    {
        try {
            $concentrators = Concentrator::with("measurementPoint.project")->get();
            foreach ($concentrators as $concentrator) {
                if ($concentrator->measurementPoint) {
                    $concentrator['available'] = 0;
                } else {
                    $concentrator['available'] = 1;
                }
            }

            return view('web.concentrators', ["concentrators" => $concentrator]);
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function get(Request $request)
    {
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

    public function update(Request $request)
    {
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
        return render_ok(["concentrator" => $concentrator_params]);
    }

    public function delete(Request $request)
    {
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

    private function handleValidate(Request $request)
    {
        return $request->validate([
            'device_id' => ['required', 'string', 'size:14', Rule::unique('concentrators')->ignore($request->id)]
        ]);
    }
}