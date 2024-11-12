<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use Exception;
use Illuminate\Http\Request;

class ConcentratorController extends Controller
{
    public function create(Request $request)
    {
        try {
            $concentrator_params = $request->only((new Concentrator)->getFillable());
            if (strlen("00" . $concentrator_params['device_id']) !== 16) {
                return render_unprocessable_entity('Concentrator device id not 64 bits');
            }
            try {
                $concentrator_id = Concentrator::insertGetId($concentrator_params);
                $concentrator = Concentrator::find($concentrator_id);
                return render_ok(["concentrator" => $concentrator]);
            } catch (Exception $e) {
                return render_unprocessable_entity("Concentrator Device ID already in use.");
            }
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function index()
    {
        try {
            return ["concentrators" => Concentrator::all()];
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function show()
    {
        try {
            return view('web.concentrators', ["concentrators" => Concentrator::all()]);
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
        try {
            $id = $request->route('id');
            $concentrator_params = $request->only((new Concentrator)->getFillable());
            debug_log('asd', [$concentrator_params]);
            $concentrator = Concentrator::find($id);
            if (!$concentrator) {
                return render_unprocessable_entity("Unable to find concentrator with id " . $id);
            }

            try {
                $concentrator->update($concentrator_params);
                return render_ok(["concentrator" => $concentrator_params]);
            } catch (Exception $e) {
                return render_unprocessable_entity("Unable to update concentrator");
            }
        } catch (Exception $e) {
            render_error($e);
        }
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
            return render_ok(["concentrator" => $concentrator]);
        } catch (Exception $e) {
            return render_error($e);
        }
    }
}
