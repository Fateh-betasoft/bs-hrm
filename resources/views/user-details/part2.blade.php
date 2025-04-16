{{-- This view is loaded via AJAX after part 1 is submitted --}}
<form id="user-details-part2-form" method="POST" action="{{ route('user-details.part2.store') }}">
    @csrf
    <h5 class="mb-3">User Details - Step 2 of 2: Address</h5>

    {{-- Address Type --}}
    <div class="mb-3">
        <label for="address_type" class="form-label">Type of Address</label>
        <select id="address_type" name="address_type" class="form-select" required>
            <option value="" disabled selected>Select...</option>
            <option value="Permanent">Permanent</option>
            <option value="Temporary">Temporary</option>
            <option value="Office">Office</option>
        </select>
        @error('address_type')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- City --}}
    <div class="mb-3">
        <label for="city" class="form-label">City</label>
        <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}" required>
        @error('city')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Pincode --}}
    <div class="mb-3">
        <label for="pincode" class="form-label">Pincode</label>
        <input type="text" id="pincode" name="pincode" class="form-control" value="{{ old('pincode') }}" required>
        @error('pincode')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Address Line 1 --}}
    <div class="mb-3">
        <label for="address_line_1" class="form-label">Address Line 1</label>
        <input type="text" id="address_line_1" name="address_line_1" class="form-control" value="{{ old('address_line_1') }}" required>
        @error('address_line_1')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Address Line 2 --}}
    <div class="mb-3">
        <label for="address_line_2" class="form-label">Address Line 2 (Optional)</label>
        <input type="text" id="address_line_2" name="address_line_2" class="form-control" value="{{ old('address_line_2') }}">
        @error('address_line_2')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-success">Submit Details</button>
</form>