<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ isset($designations) ? __('Edit Designation') : __('Create Designation') }}
            </h2>

            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>

        <hr class="mb-4">
        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="mb-4 text-red-600 bg-red-50 p-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Form --}}
        <form
            action="{{ isset($designations) ? route('designations.update', $designations->id) : route('designations.store') }}"
            method="POST">
            @csrf
            @if (isset($designations))
                @method('PUT')
            @endif

            {{-- Department Code --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Designation Code</label>
                <input type="text" name="code" value="{{ old('code', $designations->designation_code ?? '') }}"
                    class="form-input w-full rounded border-gray-300 w-25" required>
            </div>

            {{-- Department Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Designation Name</label>
                <input type="text" name="name" value="{{ old('name', $designations->designation_name ?? '') }}"
                    class="form-input w-full rounded border-gray-300 w-25" required>
            </div>

            {{-- Unit Dropdown
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Unit</label>
                <select name="unit_id" class="form-select w-full rounded border-gray-300 w-25" required>
                    <option value="">-- Select Unit --</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}"
                            {{ old('unit_id', $department->unit_id ?? '') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            {{-- Status --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="form-select w-full rounded border-gray-300 w-25" required>
                    <option value="active"
                        {{ old('status', $designations->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive"
                        {{ old('status', $designations->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end">
                <a href="{{ route('departments.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                    {{ isset($designations) ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </x-slot>
</x-app-layout>
