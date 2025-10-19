<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactCustomField;
use App\Models\MergedContact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('customFields')->where('is_active', 1)->get();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'custom_fields' => 'nullable|array',
            'custom_fields.*.name' => 'required_with:custom_fields|string|max:255',
            'custom_fields.*.type' => 'required_with:custom_fields|string|in:text,textarea,number,date,email',
            'custom_fields.*.value' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Contact name is required.',
            'custom_fields.*.name.required_with' => 'Each custom field must have a name.',
            'custom_fields.*.type.required_with' => 'Each custom field must have a type.',
        ]);

        $contact = Contact::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => 1,
        ]);

        if (!empty($validated['custom_fields'])) {
            foreach ($validated['custom_fields'] as $field) {
                $contact->customFields()->create([
                    'field_name' => $field['name'],
                    'field_value' => $field['value'] ?? '',
                ]);
            }
        }

        return redirect()->route('contacts.index')->with('success', 'Contact added successfully.');
    }


    public function showMergeModal($id)
    {
        $contact = Contact::findOrFail($id);
        $otherContacts = Contact::where('id', '!=', $id)
            ->where('is_active', 1)
            ->get();

        return view('contacts.merge_modal', compact('contact', 'otherContacts'));
    }

    public function mergeContacts(Request $request)
    {
        $validated = $request->validate([
            'master_contact_id' => 'required|exists:contacts,id',
            'secondary_contact_id' => 'required|exists:contacts,id|different:master_contact_id',
        ]);

        $master = Contact::with('customFields')->findOrFail($validated['master_contact_id']);
        $secondary = Contact::with('customFields')->findOrFail($validated['secondary_contact_id']);

        $masterEmails = collect(explode(',', $master->email));
        $secondaryEmails = collect(explode(',', $secondary->email));
        $mergedEmails = $masterEmails->merge($secondaryEmails)->filter()->unique()->implode(',');

        $masterPhones = collect(explode(',', $master->phone));
        $secondaryPhones = collect(explode(',', $secondary->phone));
        $mergedPhones = $masterPhones->merge($secondaryPhones)->filter()->unique()->implode(',');

        $master->update([
            'email' => $mergedEmails,
            'phone' => $mergedPhones,
        ]);

        foreach ($secondary->customFields as $field) {
            $existing = $master->customFields()->where('field_name', $field->field_name)->first();

            if ($existing) {
                if (trim($existing->field_value) !== trim($field->field_value)) {
                    $existing->field_value = trim($existing->field_value . '; ' . $field->field_value, '; ');
                    $existing->save();
                }
            } else {
                $master->customFields()->create([
                    'field_name' => $field->field_name,
                    'field_value' => $field->field_value,
                ]);
            }
        }

        $secondary->update(['is_active' => 0]);

        MergedContact::create([
            'master_contact_id' => $master->id,
            'merged_contact_id' => $secondary->id,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contacts merged successfully.');
    }
}
