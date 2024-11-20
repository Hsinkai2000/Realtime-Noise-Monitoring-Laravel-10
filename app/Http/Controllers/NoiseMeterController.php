<?php

namespace App\Http\Controllers;

use App\Models\NoiseMeter;
use Exception;
use Illuminate\Http\Request;

class NoiseMeterController extends Controller
{
    public function create(Request $request)
    {
        try {
            $noise_meter_params = $request->only((new NoiseMeter)->getFillable());
            debug_log($noise_meter_params);
            if (strlen($noise_meter_params['serial_number']) !== 4) {
                return render_unprocessable_entity('Noise meter serial number not 16 bits');
            }
            try {
                $noise_meter_id = NoiseMeter::insertGetId($noise_meter_params);
                $noise_meter = NoiseMeter::find($noise_meter_id);
            } catch (Exception $e) {
                return render_unprocessable_entity('Noise meter serial number already in use:');
            }
            return render_ok(["noise_meter" => $noise_meter, "noise_meters" => NoiseMeter::all()]);
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function show()
    {
        try {
            return view('web.noiseMeters', ["noise_meters" => NoiseMeter::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }
    public function index()
    {
        try {
            return ["noise_meters" => NoiseMeter::all()];
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
        try {
            $id = $request->route('id');
            $noise_meter_params = $request->only((new NoiseMeter)->getFillable());
            $noise_meter = NoiseMeter::find($id);
            debug_log('here');
            if (!$noise_meter) {
                debug_log('here1');
                return render_unprocessable_entity("Unable to find noise meter with id " . $id);
            }

            try {
                $noise_meter->update($noise_meter_params);
                return render_ok(["noise_meter" => $noise_meter, "noise_meters" => NoiseMeter::all()]);
            } catch (Exception $e) {
                debug_log('here2');
                return render_unprocessable_entity("Unable to update noise meter");
            }
        } catch (Exception $e) {
            render_error($e->getMessage());
        }
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
}