<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Exception;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    public function create(Request $request)
    {
        try {
            $contact_params = $request->only((new Contact)->getFillable());
            $contact_id = Contact::insertGetId($contact_params);
            $contact = Contact::find($contact_id);
            return render_ok(["contact" => $contact]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
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
        try {
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
        } catch (Exception $e) {
            render_error($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->route('id');
            $contact = Contact::find($id);
            if (!$contact) {
                return render_unprocessable_entity("Unable to find contact with id " . $id);
            }
            if (!$contact->delete()) {
                throw new Exception("Unable to delete contact");
            }
            return render_ok(["contact" => $contact]);
        } catch (Exception $e) {
            return render_error($e->getMessage());
        }
    }
}
