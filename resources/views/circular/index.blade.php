<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Circulars') }}

                <a href="https://drive.google.com/file/d/1aVJ01FG3wVKQd1iK8CjqvJds_AWzuJgr/view?usp=sharing"
                    target="_blank" class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
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
        <div class="col-md-12">
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            <div class="card-body">
                <div class="d-flex justify-content-start mb-3">
                    @hasanyrole(['hr', 'admin'])
                    <a href="{{ route('circulars.create') }}" class="btn btn-success shadow-sm">
                        <i class="bi bi-person-plus"> Create Circular </i>
                    </a>
                    @endhasanyrole
                </div>
                <table id="circularsTable" class="table table-bordered">
                    <thead class="text-dark">
                        <tr>
                            <th style="width: 10px;">S.No</th>
                            <th style="width: 30px;">Created Date</th>
                            <th style="width: 30px;">Circular Number</th>
                            <th style="width: 300px;">Circukar Name</th>
                            <th style="width: 70px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($circulars as $index => $circular)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($circular->circular_date)->format('d-m-Y') }}</td>
                            <td>{{ $circular->circular_no }}</td>
                            <td>{{ $circular->circular_name }}</td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#pdfModal{{ $circular->id }}">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- PDF Viewer Modals --}}
        @foreach ($circulars as $circular)
        <div class="modal fade" id="pdfModal{{ $circular->id }}" tabindex="-1"
            aria-labelledby="pdfModalLabel{{ $circular->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Circular - {{ $circular->circular_no }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: 80vh;">
                        <iframe
                            src="{{ asset('pdfjs/web/viewer.html') }}?file={{ urlencode(asset('storage/' . $circular->file_path)) }}#toolbar=0"
                            width="100%" height="100%" style="border: none;">
                        </iframe>
                    </div>
                    {{-- <div class="modal-footer">
                        <a href="{{ asset('storage/' . $circular->file_path) }}" class="btn btn-success" target="_blank">
                    <i class="bi bi-download"></i> Download PDF
                    </a>
                </div> --}}
            </div>
        </div>
        </div>
        @endforeach
        {{-- @endsection --}}
        @section('scripts')
        <script src="https://cdn.datatables.net/2.3.2/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#circularsTable').DataTable({
                    "order": [], // disable initial ordering
                    "pageLength": 10
                });
            });
        </script>
        @endsection
    </x-slot>
</x-app-layout>