<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ContactsController extends Controller
{
    public function create(Request $request)
    {
        debug_log($request->only((new Contact)->getFillable()));
        debug_log($request->get('project_id'));
        $this->handleContactValidation($request);
        $contact_params = $request->only((new Contact)->getFillable());
        $contact_id = Contact::insertGetId($contact_params);
        $contact = Contact::find($contact_id);
        return render_ok(["contact" => $contact]);
    }


    public function index()
    {
        try {
            return render_ok(["contacts" => Contact::all()]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    public function get(Request $request)
    {
        try {
            $id = $request->route('id');
            $contact = Contact::where('project_id', $id)->get();
            if (!$contact) {
                return render_unprocessable_entity("Unable to find contact with id " . $id);
            }

            return render_ok(["contact" => $contact]);
        } catch (Exception $e) {

            return render_error($e);
        }
    }

    public function update(Request $request)
    {
        $this->handleContactValidation($request);
        $id = $request->route('id');
        $contact_params = $request->only((new Contact)->getFillable());
        $contact = Contact::find($id);
        if (!$contact) {
            return render_unprocessable_entity("Unable to find contact with id " . $id);
        }

        if (!$contact->update($contact_params)) {
            throw new Exception("Unable to update contact");
        }


        return render_ok(["contact" => $contact]);
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $contact = Contact::find($id);
            $project_id = $contact->project_id;
            if (!$contact) {
                return render_unprocessable_entity("Unable to find contact with id " . $id);
            }
            if (!$contact->delete()) {
                throw new Exception("Unable to delete contact");
            }
            $contacts = Contact::where('project_id', $project_id)->get();
            return render_ok(["contact" => $contact, "contacts" => $contacts]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    private function handleContactValidation(Request $request)
    {
        return $request->validate([
            'contact_person_name' => 'required',
            'designation' => 'required',
            'phone_number' => 'digits:8'
        ]);
    }
}