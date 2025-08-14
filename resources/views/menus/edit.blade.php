<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Edit Menu') }}
            </h2>

            <a href="{{ route('menus.index') }}" class="text-sm text-red-700 no-underline">
                &larr; {{ __('Back') }}
            </a>
        </div>

        <hr class="mb-4">
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Name</label>
                <input type="text" name="name"
                    class="form-input w-full rounded border-gray-300"
                    value="{{ old('name', $menu->name) }}" required>
            </div>

            {{-- Uncomment if you want icon support --}}
            {{--
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Icon (optional)</label>
                <input type="text" name="icon"
                    class="form-input w-full rounded border-gray-300"
                    value="{{ old('icon', $menu->icon) }}">
            </div>
            --}}

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">URL</label>
                <input type="text" name="url"
                    class="form-input w-full rounded border-gray-300"
                    value="{{ old('url', $menu->url) }}">
            </div>

            {{-- Uncomment if you want parent/order fields --}}
            {{--
            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Parent Menu</label>
                <select name="parent_id" class="form-select w-full rounded border-gray-300">
                    <option value="">-- None --</option>
                    @foreach ($parents as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm text-gray-700">Order</label>
                <input type="number" name="order"
                    class="form-input w-full rounded border-gray-300"
                    value="{{ old('order', $menu->order) }}">
            </div>
            --}}

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $menu->is_active) ? 'checked' : '' }}
                        class="form-checkbox">
                    <span class="ml-2">Active</span>
                </label>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('menus.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
            </div>
        </form>
    </x-slot>
</x-app-layout>
