<x-app-layout>
    {{-- ============================
        Circulars Index Page
        Author   : Your Name
        Module   : Circular Management
        Purpose  : Display list of circulars with PDF preview modal
        Version  : 1.0.0
        History  :
            v1.0.0 - Initial implementation with DataTable + PDF.js modal viewer
    ============================ --}}

    {{-- Page Header --}}
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Circulars') }}

                {{-- Info Icon (Linked to Google Drive for Reference/Help Docs) --}}
                <a href="https://drive.google.com/file/d/1aVJ01FG3wVKQd1iK8CjqvJds_AWzuJgr/view?usp=sharing"
                    target="_blank" class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 m-2" viewBox="0 0 20 20" fill="red">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-2 4a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 00-1-1h-2a1 1 0 00-1 1z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </h2>

            {{-- Back Button --}}
            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>

        <hr class="mb-4">

        {{-- Flash Error Message --}}
        <div class="col-md-12">
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card-body">
                {{-- Action Buttons --}}
                <div class="d-flex justify-content-start mb-3">
                    @hasanyrole(['hr', 'admin'])
                    <a href="{{ route('circulars.create') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        + Create Circulars
                    </a>
                    @endhasanyrole
                </div>

                {{-- Circulars DataTable --}}
                <table id="circularsTable" class="table table-bordered">
                    <thead class="text-dark">
                        <tr>
                            <th style="width: 10px;">S.No</th>
                            <th style="width: 30px;">Created Date</th>
                            <th style="width: 30px;">Circular Number</th>
                            <th style="width: 300px;">Circular Name</th>
                            <th style="width: 70px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($circulars as $index => $circular)
                        <tr>
                            {{-- Serial Number --}}
                            <td>{{ $index + 1 }}</td>

                            {{-- Circular Date (formatted as DD-MM-YYYY) --}}
                            <td>{{ \Carbon\Carbon::parse($circular->circular_date)->format('d-m-Y') }}</td>

                            {{-- Circular Number --}}
                            <td>{{ $circular->circular_no }}</td>

                            {{-- Circular Name --}}
                            <td>{{ $circular->circular_name }}</td>

                            {{-- Actions Column --}}
                          <td class="text-center">
    <a href="#fileModal{{ $circular->id }}" data-bs-toggle="modal" data-bs-target="#fileModal{{ $circular->id }}">
        View
    </a>

    <!-- Modal for this specific file -->
    <div class="modal fade" id="fileModal{{ $circular->id }}" tabindex="-1" aria-labelledby="fileModalLabel{{ $circular->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Larger modal size -->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Dynamic Title -->
                    <h5 class="modal-title" id="fileModalLabel{{ $circular->id }}">
                        {{ $circular->id }} / {{ $circular->circular_name ?? 'Untitled File' }} <!-- Dynamic or default title -->
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Disable right-click on the iframe for download prevention -->
                    <iframe src="https://docs.google.com/viewer?embedded=true&url={{ urlencode(asset('storage/' . $circular->file_path)) }}" width="100%" height="600px" frameborder="0" oncontextmenu="return false;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</td>




                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ============================
            PDF Viewer Modals
            Each Circular opens in Bootstrap Modal with PDF.js viewer
        ============================ --}}
     

        <div
            </div>


            {{-- Page Scripts --}}
            @section('scripts')
            {{-- DataTables JS --}}
            <script src="https://cdn.datatables.net/2.3.2/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#circularsTable').DataTable({
                        "order": [], // disable initial ordering
                        "pageLength": 10 // show 10 rows per page
                    });
                });
            </script>
            @endsection
    </x-slot>
</x-app-layout>