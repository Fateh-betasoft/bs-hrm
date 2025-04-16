@extends('layouts.app')

@section('title', 'User Details - Part 1')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">User Details - Step 1 of 2</div>
                <div class="card-body" id="form-part-1-container">
                    <div id="form-messages" class="mb-3"></div>
                    <form id="user-details-part1-form" method="POST" action="{{ route('user-details.part1.store') }}">
                        @csrf

                        {{-- Marital Status --}}
                        <div class="mb-3">
                            <label for="marital_status" class="form-label">Marital Status</label>
                            <select id="marital_status" name="marital_status" class="form-select" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Gender --}}
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="" disabled selected>Select...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Date of Birth --}}
                        <div class="mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Employee Code --}}
                        <div class="mb-3">
                            <label for="emp_code" class="form-label">Employee Code</label>
                            <input type="text" id="emp_code" name="emp_code" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Position --}}
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" id="position" name="position" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        {{-- Father's Name --}}
                        <div class="mb-3">
                            <label for="father_name" class="form-label">Father's Name</label>
                            <input type="text" id="father_name" name="father_name" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include jQuery if not already included globally --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#user-details-part1-form').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize();
            var submitButton = form.find('button[type="submit"]');
            var formMessages = $('#form-messages');
            var formContainer = $('#form-part-1-container');

            // Clear previous errors and messages
            formMessages.html('').removeClass('alert alert-danger alert-success');
            form.find('.is-invalid').removeClass('is-invalid');
            form.find('.invalid-feedback').text('');

            // Disable submit button
            submitButton.prop('disabled', true).text('Processing...');

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'html', // Expecting HTML response (the part 2 view)
                success: function(response) {
                    // Replace the content of the container with the new form (part 2)
                    formContainer.html(response);
                    // No need to re-enable button as the form is replaced
                },
                error: function(xhr) {
                    submitButton.prop('disabled', false).text('Next'); // Re-enable button
                    if (xhr.status === 422) {
                        // Handle validation errors
                        var errors = xhr.responseJSON.errors;
                        formMessages.html('Please correct the errors below.').addClass('alert alert-danger');
                        $.each(errors, function(key, value) {
                            var input = $('#' + key);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(value[0]);
                        });
                    } else {
                        // Handle other errors
                        formMessages.html('An unexpected error occurred. Please try again.').addClass('alert alert-danger');
                        console.error('AJAX Error:', xhr.responseText);
                    }
                }
            });
        });
    });
</script>
@endsection