<?php

namespace App\Http\Controllers;

use App\Models\Concentrator;
use App\Models\MeasurementPoint;
use App\Models\NoiseMeter;
use App\Models\SoundLimit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class MeasurementPointController extends Controller
{

    public function show($id)
    {
        $measurement_point = MeasurementPoint::find($id);
        return view('web.measurementPoint', ['measurementPoint' => $measurement_point])->render();
    }

    public function create(Request $request)
    {
        $measurement_point_params = $request->only((new MeasurementPoint)->getFillable());
        $confirmation = $request->get('confirmation');
        debug_log($measurement_point_params);
        if (!isset($measurement_point_params['concentrator_id'])) {
            $measurement_point_params['concentrator_id'] = null;
        }
        if (!isset($measurement_point_params['noise_meter_id'])) {
            $measurement_point_params['noise_meter_id'] = null;
        }

        $point = MeasurementPoint::where([['project_id', $measurement_point_params['project_id']], ['point_name', $measurement_point_params['point_name']]])->get();

        if ($point->isNotEmpty()) {
            \Log::info("1");
            return render_unprocessable_entity('Please ensure measurement point name is unique within project');
        }
        \Log::info("3");
        try {
            \Log::info("4");
            if (isset($measurement_point_params['concentrator_id']) || isset($measurement_point_params['noise_meter_id'])) {

                $data = $this->check_device_availability($measurement_point_params['concentrator_id'], $measurement_point_params['noise_meter_id']);
                \Log::info($data);
                if (!empty($data) && !$confirmation) {
                    return render_unprocessable_entity($data);
                }

                $this->update_device_usage($measurement_point_params['concentrator_id'], $measurement_point_params['noise_meter_id']);
            }
            \Log::info("5");

            $measurement_point_id = MeasurementPoint::insertGetId($measurement_point_params);
            \Log::info($measurement_point_id);
            $measurement_point = MeasurementPoint::find($measurement_point_id);
            \Log::info($measurement_point);
            return render_ok(["measurement_point" => $measurement_point]);
        } catch (Exception $e) {
            return render_error($e);
        }
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
            $measurementPoint = MeasurementPoint::where('project_id', $id)->get();
            if (!$measurementPoint) {
                return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
            }
            $data = $measurementPoint->map(function ($measurementPoint) {
                $concentrator = $measurementPoint->concentrator;
                $noise_meter = $measurementPoint->noiseMeter;
                debug_log('data', [$measurementPoint->id]);
                $data = [
                    'id' => $measurementPoint->id,
                    'project_id' => $measurementPoint->project_id,
                    'noise_meter_id' => $measurementPoint->noise_meter_id,
                    'concentrator_id' => $measurementPoint->concentrator_id,
                    'point_name' => $measurementPoint->point_name,
                    'device_location' => $measurementPoint->device_location,
                    'remarks' => $measurementPoint->remarks,
                    'data_status' => $measurementPoint->check_data_status(),
                    'category' => $measurementPoint->soundLimit->category,
                ];

                if ($concentrator) {
                    $data['concentrator_label'] = $concentrator->concentrator_label;
                    $data['device_id'] = $concentrator->device_id;
                    $data['battery_voltage'] = $concentrator->battery_voltage;
                    $data['concentrator_csq'] = $concentrator->concentrator_csq;
                    $data['last_communication_packet_sent'] = $concentrator->last_communication_packet_sent ? $concentrator->last_communication_packet_sent->format('Y-m-d H:m:s') : "";
                }

                if ($noise_meter) {
                    $data['noise_meter_label'] = $noise_meter->noise_meter_label;
                    $data['serial_number'] = $noise_meter->serial_number;
                }
                $data['soundLimit'] = $measurementPoint->soundLimit;

                return $data;
            });

            return render_ok(['measurement_point' => $data]);
        } catch (Exception $e) {
            return render_error($e);
        }
    }

    public function update(Request $request)
    {
        $id = $request->route('id');
        $measurement_point_params = $request->only((new MeasurementPoint)->getFillable());
        $confirmation = $request->get('confirmation');

        if (!isset($measurement_point_params['concentrator_id'])) {
            $measurement_point_params['concentrator_id'] = null;
        }
        if (!isset($measurement_point_params['noise_meter_id'])) {
            $measurement_point_params['noise_meter_id'] = null;
        }
        $point = MeasurementPoint::where([['project_id', $measurement_point_params['project_id']], ['point_name', $measurement_point_params['point_name']], ['id', '!=', $id]])->get();

        if ($point->isNotEmpty()) {
            return render_unprocessable_entity('Please ensure measurement point name is unique within project');
        }

        if (isset($measurement_point_params['concentrator_id']) || isset($measurement_point_params['noise_meter_id'])) {

            $data = $this->check_device_availability($measurement_point_params['concentrator_id'], $measurement_point_params['noise_meter_id'], $id);
            if (!empty($data) && !$confirmation) {
                return render_unprocessable_entity($data);
            }
            $this->update_device_usage($measurement_point_params['concentrator_id'], $measurement_point_params['noise_meter_id']);
        }
        $measurement_point = MeasurementPoint::find($id);
        if (!$measurement_point) {
            return render_unprocessable_entity("Unable to find Measurement point with id " . $id);
        }
        try {
            $measurement_point->update($measurement_point_params);
            return render_ok(["measurement_point" => $measurement_point]);
        } catch (Exception $e) {
            return render_error($e);
        };
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
        \log::info($data);
        if ($concentrator_id != null) {
            $concentratorMP = MeasurementPoint::where('concentrator_id', $concentrator_id)->first();

            if ($concentratorMP != null) {
                if ($id != null) {
                    if ($concentratorMP[0]->id != $id) {
                        $data['concentrator'] = $concentratorMP[0];
                    }
                } else {
                    $data['concentrator'] = $concentratorMP[0];
                }
            }
        }


        \log::info($data);
        if ($noise_meter_id != null) {
            $noise_meterMP = MeasurementPoint::where('noise_meter_id', $noise_meter_id)->first();

            if ($noise_meterMP != null) {
                if ($id != null) {
                    if ($noise_meterMP[0]->id != $id) {
                        $data['noise_meter'] = $noise_meterMP[0];
                    }
                } else {

                    $data['noise_meter'] = $noise_meterMP[0];
                }
            }
        }

        \log::info($data);
        return $data;
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
}