{{-- // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
{{-- <x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Feedback Details') }}
            </h2>

            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4"> --}}
{{-- @can('create employees') --}}
{{-- <a href="{{ route('feedbacks.create') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    + Create Feedbacks
                </a> --}}
{{-- @endcan --}}
{{--



        </div>
    </x-slot>
</x-app-layout> --}}

{{-- // /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Create Feedback') }}

                <a href="https://drive.google.com/file/d/12YRxNsSWLgcjBO5KNQuCuPdzWdGO8UXM/view?usp=sharing"
                    class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 m-2" viewBox="0 0 20 20" fill="red">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-2 4a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 00-1-1h-2a1 1 0 00-1 1z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </h2>

            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">
        <!-- Main content container with the form -->
        <div class="w-50">
            {{-- Feedback Form --}}
            <form method="POST" action="{{ route('feedback.store') }}" class="space-y-6">
                @csrf
                <input type="text" name="emp_id" id="emp_id" value="{{ auth()->user()->id }}" hidden
                    class="form-control-plaintext bg-gray-100">
                <!-- Employee Name (Auto-populated) -->
                <div class="mb-3">
                    <label for="emp_name" class="form-label">Employee Name</label>
                    <input type="text" name="emp_name" id="emp_name" value="{{ auth()->user()->name }}" readonly
                        class="form-control-plaintext bg-gray-100">
                </div>

                <!-- Feedback Text Area -->
                <div class="mb-3">
                    <label for="feedback_type" class="form-label">Feedback Type</label>
                    <select name="feedback_type" id="feedback_type" required class="form-control">
                        <option value="">Select Feedback Type</option>
                        <option value="ticket">Issue / Ticket </option>
                        <option value="feedback">Feedback</option>
                        <option value="suggestion">Suggestion</option>
                    </select>
                </div>

                <!-- Areas of Improvement Text Area -->
                <div class="mb-3">
                    <label for="areas_of_improvement" class="form-label">Comments</label>
                    <textarea name="areas_of_improvement" id="areas_of_improvement" rows="4" class="form-control"></textarea>
                </div>
        </div>
        <!-- Submit Button -->
        <div class="d-grid gap-2 w-25 mb-5">
            <button type="submit" class="btn btn-primary">
                Submit Feedback
            </button>
        </div>
        </form>

        </div>

    </x-slot>
</x-app-layout>
