<?php

namespace App\Http\Controllers;

use App\Models\NoiseData;
use App\Models\NoiseMeter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NoiseMeterController extends Controller
{
    public function create(Request $request)
    {
        $this->handleValidate($request);
        $noise_meter_params = $request->only((new NoiseMeter)->getFillable());

        $noise_meter_id = NoiseMeter::insertGetId($noise_meter_params);
        $noise_meter = NoiseMeter::find($noise_meter_id);
        return render_ok(["noise_meter" => $noise_meter, "noise_meters" => NoiseMeter::all()]);
    }

    public function show()
    {
        try {

            return view('web.noiseMeters', ["noise_meters" => NoiseMeter::with("measurementPoint.project")->get()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }
    public function index()
    {
        try {
            $noise_meters = NoiseMeter::all();
            foreach ($noise_meters as $noise_meter) {
                $noise_meter['isAvailable'] = $noise_meter->isAvailable();
            }
            return $noise_meters;
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {
            $id = $request->route('id');
            $noise_meter = NoiseMeter::find($id);
            if (!$noise_meter) {
                return render_unprocessable_entity("Unable to find noise meter with id " . $id);
            }
            return render_ok(["noise_meter" => $noise_meter]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $this->handleValidate($request);
        $id = $request->route('id');
        $noise_meter_params = $request->only((new NoiseMeter)->getFillable());
        $noise_meter = NoiseMeter::find($id);

        if (!$noise_meter) {
            return render_unprocessable_entity("Unable to find noise meter with id " . $id);
        }

        $noise_meter->update($noise_meter_params);
        return render_ok(["noise_meter" => $noise_meter, "noise_meters" => NoiseMeter::all()]);
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $noise_meter = NoiseMeter::find($id);
            if (!$noise_meter) {
                return render_unprocessable_entity("Unable to find noise meter with id " . $id);
            }
            if (!$noise_meter->delete()) {
                throw new Exception("Unable to delete noise meter");
            }
            return render_ok(["noise_meter" => $noise_meter, "noise_meters" => NoiseMeter::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function deletable(Request $request)
    {
        $id = $request->route('id');
        $noisedata = NoiseData::where('noise_meter_id', $id)->first();

        if ($noisedata) {
            return render_unprocessable_entity([false]);
        }
        return render_ok([true]);
    }

    private function handleValidate(Request $request)
    {
        return $request->validate([
            'serial_number' => ['required', 'string', 'size:4', Rule::unique('noise_meters')->ignore($request->id)],
            'brand' => 'required',
            'last_calibration_date' => 'required',


        ]);
    }
}
