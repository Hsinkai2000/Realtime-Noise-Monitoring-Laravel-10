<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ContactsController extends Controller
{
    public function create(Request $request)
    {
        if (Auth::user()) {
            $this->handleContactValidation($request);
            $contact_params = $request->only((new Contact)->getFillable());
            $contact_id = Contact::insertGetId($contact_params);
            $contact = Contact::find($contact_id);
            return render_ok(["contact" => $contact]);
        }
    }


    public function index()
    {
        $user = Auth::user();

        if (Gate::authorize('adminUser', $user)) {
            try {
                return render_ok(["contacts" => Contact::all()]);
            } catch (Exception $e) {
                return render_error($e->getMessage());
            }
        }
    }

    public function get(Request $request)
    {
        $user = Auth::user();
        $id = $request->route('id');
        $project = Project::find($id);
        if (Gate::authorize('viewOnlyGuestProject', [$project, $user])) {
            try {
                $contact = Contact::where('project_id', $id)->get();
                if (!$contact) {
                    return render_unprocessable_entity("Unable to find contact with id " . $id);
                }

                return render_ok(["contact" => $contact]);
            } catch (Exception $e) {

                return render_error($e);
            }
        }
    }

    public function update(Request $request)
    {
        $this->handleContactValidation($request);
        $id = $request->route('id');
        $contact_params = $request->only((new Contact)->getFillable());
        $contact = Contact::find($id);
        $user = Auth::user();

        if (Gate::authorize('viewOnlyGuestProject', [$contact->project, $user])) {
            if (!$contact) {
                return render_unprocessable_entity("Unable to find contact with id " . $id);
            }

            if (!$contact->update($contact_params)) {
                throw new Exception("Unable to update contact");
            }


            return render_ok(["contact" => $contact]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = Auth::user();
            $id = $request->route('id');
            $contact = Contact::find($id);
            $project_id = $contact->project_id;
            if (Gate::authorize('viewOnlyGuestProject', [$contact->project, $user])) {
                if (!$contact) {
                    return render_unprocessable_entity("Unable to find contact with id " . $id);
                }
                if (!$contact->delete()) {
                    throw new Exception("Unable to delete contact");
                }
                $contacts = Contact::where('project_id', $project_id)->get();
                return render_ok(["contact" => $contact, "contacts" => $contacts]);
            }
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }

    private function handleContactValidation(Request $request)
    {
        return $request->validate([
            'contact_person_name' => 'required',
            'designation' => 'required',
            'phone_number' => 'numeric|digits:8|nullable',
        ]);
    }
}
