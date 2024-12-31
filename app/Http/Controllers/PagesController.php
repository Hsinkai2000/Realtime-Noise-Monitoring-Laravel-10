<?php

namespace App\Http\Controllers;

use App\Libraries\GeoscanLib;
use App\Models\Concentrator;
use App\Models\NoiseData;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PagesController extends Controller
{

    public function input(Request $request)
    {
        $geoscanLib = new GeoscanLib($request->all());
        if (!$this->initial_conditions_valid($geoscanLib)) {
            return;
        }
        switch ($geoscanLib->message_type()) {
            case 0x00:
                \Log::debug("message 0");
                $this->message_0_callback($request, $geoscanLib);
                break;
            case 0x01:
                \Log::debug("message 1");
                $this->message_1_callback($request, $geoscanLib);
                break;
            default:
                \Log::debug("message default");
                break;
        }
    }

    private function message_0_callback($request, GeoscanLib $geoscanLib)
    {
        try {
            $concentrator = $geoscanLib->concentrator();
            $this->check_message_0_conditions($concentrator);
            $s_values = $geoscanLib->summary_values();
            $this->updateConcentrator($request, $s_values, $concentrator);
            Log::debug('Concentrator updated successfully');
            render_ok("ok");
        } catch (Exception $e) {
            render_unprocessable_entity($e->getMessage());
        }
    }

    private function message_1_callback($request, GeoscanLib $geoscanLib)
    {
        try {
            $noise_meter = $geoscanLib->noise_meter();
            $concentrator = $geoscanLib->concentrator();

            $this->check_message_1_conditions($noise_meter, $geoscanLib, $concentrator);

            $measurement_point = $noise_meter->measurementPoint;
            $s_values = $geoscanLib->summary_values();
            $noise_data_params = $this->noise_data_params($geoscanLib, $s_values);

            try {

                $noise_data_id = DB::table('noise_data')->insertGetId($noise_data_params);
                $noise_data = NoiseData::find($noise_data_id);
                $this->updateConcentrator($request, $s_values, $concentrator);

                $ndevice_params = $this->prepareNdeviceParams($noise_data, $measurement_point);
                $this->update_measurement_point($measurement_point, $ndevice_params);
                $measurement_point->check_last_data_for_alert();
                Log::debug('Record Successfully updated', ['noise_data' => $noise_data]);
                render_ok("Record Successfully updated");
            } catch (Exception $e) {
                throw new Exception("Error processing noise data : " . $e);
            }
        } catch (Exception $e) {
            render_unprocessable_entity($e->getMessage());
        }
    }

    private function prepareNdeviceParams($noiseData, $measurementPoint)
    {
        $ndeviceParams = ['inst_leq' => $noiseData->leq];

        if ($measurementPoint->dose_flag_reset()) {
            $ndeviceParams = array_merge($ndeviceParams, [
                'leq_temp' => $noiseData->leq,
                'dose_flag' => 0,
            ]);
        }

        return $ndeviceParams;
    }

    private function update_measurement_point($measurement_point, $ndevice_params)
    {
        try {
            $measurement_point->update($ndevice_params);
        } catch (Exception $e) {
            Log::error('Failed to update measurement point', [
                'measurement point id' => $measurement_point->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function updateConcentrator(Request $request, array $s_values, Concentrator $concentrator)
    {
        try {
            $updatedValues = $this->prepareUpdatedValues($request, $s_values);
            $concentrator->update($updatedValues);
        } catch (\Exception $e) {
            Log::error('Failed to update concentrator', [
                'concentrator_id' => $concentrator->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function prepareUpdatedValues(Request $request, array $s_values)
    {
        return [
            'last_assigned_ip_address' => $request->ip(),
            'last_communication_packet_sent' => $this->getCurrentTime(),
            'battery_voltage' => $this->getBatteryVoltage($s_values),
            'concentrator_hp' => $s_values['ConcentratorHp'] ?? null,
            'concentrator_csq' => $s_values['CsqParam'] ?? null,
        ];
    }

    private function getBatteryVoltage($s_values)
    {
        return isset($s_values['AdcBattVolt']) ? $s_values['AdcBattVolt'] / 100.00 : null;
    }

    private function getCurrentTime()
    {
        $currentTime = new DateTime('now', new DateTimeZone('UTC'));
        $currentTime->modify('+8 hours');
        return $currentTime->format('Y-m-d H:i:s');
    }

    private function noise_data_params($geoscanLib, $s_values)
    {
        try {
            $noise_data_value = $geoscanLib->noise_data_value();
            $noise_leq = empty($noise_data_value) ? -1 : round($noise_data_value['NoiseData'], 2);

            $round_time = ceil($s_values['Timestamp'] / 300);

            $seconds = 300 * $round_time;
            $time = (new DateTime())->setTimestamp($seconds);

            $noise_data = [
                'measurement_point_id' => $geoscanLib->noise_meter()->measurementPoint->id,
                'leq' => $noise_leq,
                'received_at' => $time->format('Y-m-d H:i:s'),
            ];

            return $noise_data;
        } catch (\Exception $e) {
            Log::error('Error creating noise data parameters', [
                'error' => $e->getMessage(),
                's_values' => $s_values,
                'geoscanLib' => $geoscanLib,
            ]);
            throw $e;
        }
    }

    private function check_message_1_conditions($noise_meter, $geoscanLib, $concentrator)
    {
        try {
            $this->noise_meter_not_valid($noise_meter, $geoscanLib);
            $this->measurement_point_empty($noise_meter);
            $this->concentrator_not_valid($concentrator);
            $this->measurement_point_no_project($noise_meter->measurementPoint);
            // $this->measurement_point_no_running_project($noise_meter->measurementPoint);
            $this->noise_meter_concentrator_same_measurement_point($noise_meter->measurementPoint, $concentrator->measurementPoint);
            $this->measurement_point_has_soundLimits($noise_meter->measurementPoint);
        } catch (Exception $e) {
            throw $e;
        };
    }

    private function noise_meter_concentrator_same_measurement_point($nm_measurement_point, $c_measurement_point)
    {
        if ($nm_measurement_point->id != $c_measurement_point->id) {
            throw new Exception('Different Measurement points tied for noise meter and concentrator');
        }
    }

    private function measurement_point_no_project($measurement_point)
    {
        if ($measurement_point && !$measurement_point->hasProject()) {
            throw new Exception('Measurement point is not tied to a project');
        }
    }

    private function measurement_point_no_running_project($measurement_point)
    {
        if ($measurement_point && !$measurement_point->has_running_project()) {
            throw new Exception('Project is not Ongoing');
        }
    }

    private function noise_meter_not_valid($noise_meter, $geoscanLib)
    {
        if ($noise_meter == null) {
            throw new Exception('Noise device is not available ' . $geoscanLib->noise_serial_number());
            return true;
        }
    }

    private function measurement_point_empty($noise_meter)
    {
        if (!$noise_meter->measurementPoint) {
            throw new Exception('Noise device is not tied to measurement point');
        }
    }

    private function measurement_point_has_soundLimits($measurement_point)
    {
        if (!$measurement_point->soundLimit) {
            throw new Exception('Measurement Point does not have associated sound limits');
        }
    }

    private function check_message_0_conditions($concentrator)
    {
        try {
            $this->concentrator_not_valid($concentrator);
            // $this->concentrator_not_running($concentrator);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function concentrator_not_valid($concentrator)
    {
        if ($concentrator == null) {
            throw new Exception('Concentrator is not available');
            return true;
        }
    }

    private function concentrator_not_running($concentrator)
    {
        if (!$concentrator->has_running_project()) {
            throw new Exception('Project is not currently running');
            return true;
        }
    }

    private function initial_conditions_valid(GeoscanLib $geoscanLib)
    {
        return (
            $this->check_params_valid($geoscanLib) &&
            $this->check_crc32_valid($geoscanLib)
        );
    }

    private function check_params_valid(GeoscanLib $geoscanLib)
    {
        if ($geoscanLib->params_not_valid()) {
            render_unprocessable_entity('Not enough parameters in the request');
            return false;
        }
        return true;
    }

    private function check_crc32_valid(GeoscanLib $geoscanLib)
    {
        if (!$geoscanLib->crc32_valid()) {
            render_unprocessable_entity('CRC32 does not match');
            return false;
        }
        return true;
    }
}
