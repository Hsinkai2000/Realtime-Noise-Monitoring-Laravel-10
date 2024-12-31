<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use App\Models\MeasurementPoint;
use App\Models\NoiseMeter;
use App\Models\SoundLimit;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class MeasurementPointController extends Controller
{

    public function show($id)
    {
        $measurement_point = MeasurementPoint::find($id);
        return view('web.measurementPoint', ['measurementPoint' => $measurement_point])->render();
    }

    public function create(Request $request)
    {
        $confirmation = $request->get('confirmation');
        if (!$confirmation) {
            $this->handleMeasurementPointCreationValidation($request);
        }

        $measurement_point_params = $request->only((new MeasurementPoint)->getFillable());

        if (!isset($measurement_point_params['concentrator_id'])) {
            $measurement_point_params['concentrator_id'] = null;
        }
        if (!isset($measurement_point_params['noise_meter_id'])) {
            $measurement_point_params['noise_meter_id'] = null;
        }

        $this->update_device_usage($measurement_point_params['concentrator_id'], $measurement_point_params['noise_meter_id']);

        $measurement_point_id = MeasurementPoint::insertGetId($measurement_point_params);
        $measurement_point = MeasurementPoint::find($measurement_point_id);
        return render_ok(["measurement_point" => $measurement_point]);
    }

    public function index()
    {
        try {
            return view("measurement_points", ['measurement_point' => MeasurementPoint::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {

            $id = $request->route('id');
            $measurementPoint = MeasurementPoint::where('project_id', $id)->with(['noiseMeter', 'concentrator', 'soundLimit'])->get();
            if (!$measurementPoint) {
                return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
            }
            $data = $measurementPoint->map(function ($measurementPoint) {
                $concentrator = $measurementPoint->concentrator;
                $noiseMeter = $measurementPoint->noiseMeter;

                return [
                    'id' => $measurementPoint->id,
                    'project_id' => $measurementPoint->project_id,
                    'noise_meter_id' => $measurementPoint->noise_meter_id,
                    'concentrator_id' => $measurementPoint->concentrator_id,
                    'point_name' => $measurementPoint->point_name,
                    'device_location' => $measurementPoint->device_location,
                    'remarks' => $measurementPoint->remarks,
                    'data_status' => $measurementPoint->check_data_status(),
                    'category' => $measurementPoint->soundLimit->category ?? null,
                    'concentrator' => $concentrator ? [
                        'label' => $concentrator->concentrator_label,
                        'device_id' => $concentrator->device_id,
                        'battery_voltage' => $concentrator->battery_voltage,
                        'concentrator_csq' => $concentrator->concentrator_csq,
                        'last_communication_packet_sent' => $concentrator->last_communication_packet_sent
                            ? $concentrator->last_communication_packet_sent->format('Y-m-d H:i:s')
                            : null,
                    ] : null,
                    'noise_meter' => $noiseMeter ? [
                        'noise_meter_label' => $noiseMeter->noise_meter_label,
                        'serial_number' => $noiseMeter->serial_number,
                    ] : null,
                ];
            });

            return response()->json($data, 200);
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function update(Request $request)
    {
        $confirmation = $request->get('confirmation');
        if (!$confirmation) {
            $this->handleMeasurementPointUpdateValidation($request);
        }
        $id = $request->route('id');
        $measurement_point_params = $request->only((new MeasurementPoint)->getFillable());

        if (!isset($measurement_point_params['concentrator_id'])) {
            $measurement_point_params['concentrator_id'] = null;
        }
        if (!isset($measurement_point_params['noise_meter_id'])) {
            $measurement_point_params['noise_meter_id'] = null;
        }

        $this->update_device_usage($measurement_point_params['concentrator_id'], $measurement_point_params['noise_meter_id']);

        $measurement_point = MeasurementPoint::find($id);
        if (!$measurement_point) {
            return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
        }
        $measurement_point->update($measurement_point_params);
        return render_ok(["measurement_point" => $measurement_point]);
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $measurement_point = MeasurementPoint::find($id);
            if (!$measurement_point) {
                return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
            }
            if (!$measurement_point->delete()) {
                throw new Exception("Unable to delete Measurement point");
            }
            return render_ok(["measurement_point" => $measurement_point]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function check_device_availability($concentrator_id, $noise_meter_id, $id = null)
    {
        $data = [];
        if ($concentrator_id != null) {
            $concentratorMP = MeasurementPoint::where('concentrator_id', $concentrator_id)->get();

            if ($concentratorMP->isNotEmpty()) {
                if ($concentratorMP[0]->id != $id) {
                    $data['concentrator'] = $concentratorMP[0]->concentrator;
                }
            }
        }

        if ($noise_meter_id != null) {
            $noise_meterMP = MeasurementPoint::where('noise_meter_id', $noise_meter_id)->get();

            if ($noise_meterMP->isNotEmpty()) {
                if ($noise_meterMP[0]->id != $id) {
                    $data['noise_meter'] = $noise_meterMP[0]->noiseMeter;
                }
            }
        }

        return $data;
    }

    private function isDeviceAvailable($id, $measurementPointid = null, $type)
    {
        if ($id === null) {
            return true;
        }

        if ($type === 'concentrator') {
            $measurementPoint = MeasurementPoint::where('concentrator_id', $id)->first();
            if ($measurementPointid != null && !empty($measurementPoint)) {
                return $measurementPointid == $measurementPoint->id;
            }
            return empty($measurementPoint);
        }

        if ($type === 'noise_meter') {
            $measurementPoint = MeasurementPoint::where('noise_meter_id', $id)->first();
            if ($measurementPointid != null && !empty($measurementPoint)) {
                return $measurementPointid == $measurementPoint->id;
            }
            return empty($measurementPoint);
        }

        return false;
    }


    private function update_device_usage($concentrator_id, $noise_meter_id)
    {
        $concentrator = MeasurementPoint::where('concentrator_id', $concentrator_id)->get();

        if ($concentrator->isNotEmpty()) {
            $concentrator[0]->concentrator_id = null;
            $concentrator[0]->save();
        }
        $noise_meter = MeasurementPoint::where('noise_meter_id', $noise_meter_id)->get();

        if ($noise_meter->isNotEmpty()) {

            $noise_meter[0]->noise_meter_id = null;

            $noise_meter[0]->save();
        }
    }

    public function handleMeasurementPointCreationValidation(Request $request)
    {
        return $request->validate([
            'point_name' => [
                'required',
                Rule::unique('measurement_points')->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->get('project_id'));
                }),
            ],
            'concentrator_id' => ['nullable', 'exists:concentrators,id', function ($attribute, $value, $fail) {
                if (!$this->isDeviceAvailable($value, null, 'concentrator')) {
                    // $fail(Concentrator::find($value)->value('concentrator_label'));
                    $concentrator = Concentrator::find($value);
                    $fail($concentrator->concentrator_label);
                }
            }],
            'noise_meter_id' => ['nullable', 'exists:noise_meters,id', function ($attribute, $value, $fail) {
                if (!$this->isDeviceAvailable($value, null, 'noise_meter')) {
                    $noiseMeter = NoiseMeter::find($value);
                    $fail($noiseMeter->noise_meter_label);
                }
            }],
        ]);
    }
    public function handleMeasurementPointUpdateValidation(Request $request)
    {
        return $request->validate([
            'point_name' => [
                'required',
                Rule::unique('measurement_points')->where(function ($query) use ($request) {
                    return $query->where('project_id', $request->get('project_id'));
                })->ignore($request->route('id')),
            ],
            'concentrator_id' => ['nullable', 'exists:concentrators,id', function ($attribute, $value, $fail) use ($request) {
                if (!$this->isDeviceAvailable($value, $request->route('id'), 'concentrator')) {
                    $concentrator = Concentrator::find($value);
                    $fail($concentrator ? $concentrator->concentrator_label : 'Concentrator not available');
                }
            }],
            'noise_meter_id' => ['nullable', 'exists:noise_meters,id', function ($attribute, $value, $fail) use ($request) {
                if (!$this->isDeviceAvailable($value, $request->route('id'), 'noise_meter')) {
                    $noiseMeter = NoiseMeter::find($value);
                    $fail($noiseMeter ? $noiseMeter->noise_meter_label : 'Noise meter not available');
                }
            }],

        ]);
    }
}
