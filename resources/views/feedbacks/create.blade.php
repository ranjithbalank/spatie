<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Create Feedback') }}
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
                    <label for="feedback_text" class="form-label">Feedback</label>
                    <textarea name="feedback_text" id="feedback_text" rows="4" required class="form-control"></textarea>
                </div>

                <!-- Areas of Improvement Text Area -->
                <div class="mb-3">
                    <label for="areas_of_improvement" class="form-label">Areas of Improvement</label>
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
