@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Merge Contacts for <strong>{{ $contact->name }}</strong></h3>

    {{-- Display validation errors --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> Please correct the following errors:
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="mergeForm" method="POST" action="{{ route('contacts.merge') }}">
        @csrf
        <input type="hidden" name="secondary_contact_id" value="{{ $contact->id }}">

        <div class="form-group">
            <label>Select Master Contact <span class="text-danger">*</span></label>
            <select name="master_contact_id" id="master_contact_id" class="form-control">
                <option value="">-- Select Master Contact --</option>
                @foreach($otherContacts as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Confirm Merge</button>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function() {
    $('#mergeForm').validate({
        rules: {
            master_contact_id: {
                required: true
            }
        },
        messages: {
            master_contact_id: {
                required: "Please select a master contact before merging."
            }
        },
        errorElement: 'small',
        errorClass: 'text-danger d-block',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });
});
</script>
@endsection
