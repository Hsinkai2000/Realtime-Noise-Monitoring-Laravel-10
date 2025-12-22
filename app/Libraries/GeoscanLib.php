<?php

namespace App\Libraries;

use App\Models\Concentrator;
use App\Models\NoiseMeter;
use Exception;

define('MESSAGE_TYPES', array(0x00, 0x01, 0x02, 0x03, 0x04));

class GeoscanLib
{
    public $device_id;
    public $summary;
    public $data;
    public $crc32;
    public $sound;

    public function __construct($attributes = [])
    {
        $this->device_id = $attributes['device_id'] ?? null;
        $this->summary = $attributes['summary'] ?? null;
        $this->data = $attributes['data'] ?? null;
        $this->crc32 = $attributes['crc32'] ?? null;
        $this->sound = $attributes['sound'] ?? null;
    }

    public function params_not_valid()
    {
        return empty($this->device_id) || empty($this->crc32) || empty($this->summary) || empty($this->data);
    }

    public function crc32_valid()
    {
        return true;
    }

    public function message_types()
    {
        return MESSAGE_TYPES;
    }

    public function message_type()
    {
        $unpacked_data = unpack('VMessageType', $this->summary);
        return $unpacked_data['MessageType'];
    }

    public function concentrator_id()
    {
        try {
            $unpacked_device_id = unpack('VConcentratorFrontValue/VConcentratorBackValue', $this->device_id);
            $concentrator_id = strtoupper(dechex($unpacked_device_id['ConcentratorFrontValue'])) . strtoupper(dechex($unpacked_device_id['ConcentratorBackValue']));
            return $concentrator_id;
        } catch (Exception $e) {
            throw new Exception('device_id > 64 bits');
        }
    }

    public function concentrator()
    {
        try {
            $concentrator = Concentrator::where('device_id', $this->concentrator_id())->first();
            if (!empty($concentrator)) {
                return $concentrator;
            }
            return null;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function noise_serial_number()
    {
        $data_pack = pack('V', [($this->summary_values())['NoiseSerial']]);

        $serial = null;

        if (($this->summary_values())['NoiseSerial'] > 0x00FFFFFF) {
            $data_array = array_values(unpack('C3chars/Astring', $data_pack));
            $str_part = $data_array[3];

            $int_packed = pack('C4', $data_array[0], $data_array[1], $data_array[2], 0);
            $int_part = array_values(unpack('V', $int_packed))[0];

            $serial = 'BJ' . $str_part . $int_part;
        } else {
            $serial = strval(($this->summary_values())['NoiseSerial']);
        }
        return $serial;
    }

    public function unpack_format()
    {
        switch ($this->message_type()) {
            case MESSAGE_TYPES[0]:
                return 'VMessageType/VConcentratorHp/vAdcBattVolt/vCsqParam';
            case MESSAGE_TYPES[1]:
                return 'VMessageType/VTimestamp/VNoiseSerial/VConcentratorHp/vAdcBattVolt/vCsqParam';
                // case MESSAGE_TYPES[2]:
                //     return 'VVvvLLCCCCSCCCCSCCCCffffCCCCCCCCfffVVV';
                // case MESSAGE_TYPES[3]:
                //     return 'VLLC';
                // case MESSAGE_TYPES[4]:
                //     return 'VVvvLLCAAAAAAAAAAAAAAAAAAAAASCCCCCCSCCCCCCS';
            default:
                return null;
        }
    }

    public function noise_meter()
    {
        return NoiseMeter::where('serial_number', $this->noise_serial_number())->first();
    }

    public function noise_data_value()
    {
        return unpack('fNoiseData', $this->data);
    }

    public function summary_values()
    {
        return unpack($this->unpack_format(), $this->summary);
    }
}
