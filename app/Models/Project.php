<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';

    public $timestamps = true;

    protected $fillable = [
        'job_number',
        'client_name',
        'end_user_name',
        'sms_count',
        'project_type',
        'billing_address',
        'project_description',
        'jobsite_location',
        'bca_reference_number',
        'status',
        'created_at',
        'updated_at',
        'completed_at'
    ];

    // Define relationships

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'project_id', 'id');
    }

    public function measurement_point(): HasMany
    {
        return $this->hasMany(MeasurementPoint::class, 'project_id', 'id');
    }

    public function contact(): HasMany
    {
        return $this->hasMany(Contact::class, 'project_id', 'id');
    }

    // Define methods
    public function isRunning()
    {
        return $this->status == 'Ongoing';
    }

    public function get_contact_details()
    {
        $contacts = [];
        foreach ($this->contact as $contact) {
            $currContact = [
                'contact_person_name' => $contact->contact_person_name,
                'phone_number' => $contact->phone_number,
                'email' => $contact->email,
            ];
            array_push($contacts, $currContact);
        }
        return $contacts;
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function deleteProject()
    {
        foreach ($this->measurement_point as $measurementPoint) {
            $measurementPoint->deleteMeasurementPoint();
        }
        foreach ($this->contact as $contact) {
            $contact->delete();
        }
        foreach ($this->user as $user) {
            $user->delete();
        }
        $this->delete();
    }
}
