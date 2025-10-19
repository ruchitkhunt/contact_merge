@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Add New Contact</h2>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#customFieldModal">
            + Add Custom Field
        </button>
    </div>

    {{-- Validation Errors --}}
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

    {{-- Contact Form --}}
    <form id="contactForm" method="POST" action="{{ route('contacts.store') }}">
        @csrf

        <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <div class="form-group" id="customFieldsSection" style="display:none;">
            <label>Custom Fields</label>
            <div id="custom-fields"></div>
        </div>

        <button type="submit" class="btn btn-success">Save Contact</button>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<div class="modal fade" id="customFieldModal" tabindex="-1" role="dialog" aria-labelledby="customFieldModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Custom Field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Field Name <span class="text-danger">*</span></label>
                    <input type="text" id="modalFieldName" class="form-control" placeholder="e.g. Company">
                    <small id="fieldNameError" class="text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Field Type <span class="text-danger">*</span></label>
                    <select id="modalFieldType" class="form-control">
                        <option value="">Select Type</option>
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="email">Email</option>
                    </select>
                    <small id="fieldTypeError" class="text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="addFieldBtn" class="btn btn-primary">Add Field</button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<script>
    let customCount = 0;

    $('#addFieldBtn').on('click', function() {
        const name = $('#modalFieldName').val().trim();
        const type = $('#modalFieldType').val();

        $('#fieldNameError').text('');
        $('#fieldTypeError').text('');

        let valid = true;
        if (!name) {
            $('#fieldNameError').text('Please enter a field name.');
            valid = false;
        }
        if (!type) {
            $('#fieldTypeError').text('Please select a field type.');
            valid = false;
        }

        if (!valid) return;

        $('#customFieldsSection').show();

        const wrapper = $(`
            <div class="border p-2 mb-2 rounded bg-light">
                <label><strong>${name}</strong> (${type})
                    <button type="button" class="close text-danger ml-2" onclick="$(this).closest('div.border').remove();">
                        &times;
                    </button>
                </label>
                <input type="hidden" name="custom_fields[${customCount}][name]" value="${name}">
                <input type="hidden" name="custom_fields[${customCount}][type]" value="${type}">
            </div>
        `);

        const input =
            type === 'textarea' ?
            $(`<textarea name="custom_fields[${customCount}][value]" class="form-control"></textarea>`) :
            $(`<input type="${type}" name="custom_fields[${customCount}][value]" class="form-control">`);

        wrapper.append(input);
        $('#custom-fields').append(wrapper);
        $('#customFieldModal').modal('hide');

        $('#modalFieldName').val('');
        $('#modalFieldType').val('');

        customCount++;
    });

    $('#modalFieldName, #modalFieldType').on('input change', function() {
        if ($(this).val().trim() !== '') {
            $(this).next('small').text('');
        }
    });

    $(document).ready(function() {
        $('#contactForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: "Please enter the contact name.",
                    minlength: "Name must be at least 2 characters long."
                },
                email: {
                    required: "Please enter the email.",
                    email: "Please enter a valid email address."
                },
                phone: {
                    required: "Please enter the phone number.",
                    digits: "Phone number must contain only digits.",
                    minlength: "Phone number must be exactly 10 digits.",
                    maxlength: "Phone number must be exactly 10 digits."
                }
            },
            errorElement: 'small',
            errorClass: 'text-danger',
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection