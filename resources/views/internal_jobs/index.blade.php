<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                {{ __('Internal Job Postings ') }}
            </h2>

            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="row justify-content-center w-full">
                <div class="col-md-12">
                    @if (session('error'))
                    <div class="alert alert-danger m-3 alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if (session('warnings') && count(session('warnings')))
                    <div class="alert alert-warning m-3 alert-dismissible fade show">
                        <strong>⚠️ Warnings:</strong>
                        <ul class="mb-0">
                            @foreach (session('warnings') as $warning)
                            <li>{{ $warning }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    {{-- <div class="card shadow"> --}}

                    <div class="card-body">
                        {{-- Tabs --}}
                        <ul class="nav nav-tabs mb-3" id="jobTabs" role="tablist">
                            <li class="nav-item">

                                <button class="nav-link active" id="jobs-tab" data-bs-toggle="tab"
                                    data-bs-target="#jobs-tab-pane" type="button" role="tab">
                                    Job Listings
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="results-tab" data-bs-toggle="tab"
                                    data-bs-target="#myhistory-tab-pane" type="button" role="tab">
                                    My Application
                                </button>
                            </li>
                            @hasanyrole(['hr', 'admin'])
                            <li class="nav-item">
                                <button class="nav-link" id="applicants-tab" data-bs-toggle="tab"
                                    data-bs-target="#applicants-tab-pane" type="button" role="tab">
                                    Job Applicants
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="results-tab" data-bs-toggle="tab"
                                    data-bs-target="#results-tab-pane" type="button" role="tab">
                                    Final Job Status
                                </button>
                            </li>
                            @endhasanyrole
                        </ul>

                        <div class="tab-content">
                            {{-- Job Listings --}}
                            <div class="tab-pane fade show active" id="jobs-tab-pane">
                                <div class="d-flex justify-content-end mb-3">
                                    @hasanyrole(['hr', 'admin'])
                                    <a href="{{ route('internal-jobs.create') }}"
                                        class="btn btn-success btn-sm shadow-sm">
                                        <i class="bi bi-plus-circle"></i> Create New Job
                                    </a>
                                    @endhasanyrole
                                </div>

                                <div class="table-responsive">
                                    <table id="ticketsTable" class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>S.No</th>
                                                <th>IJP ID</th>
                                                <th>Role</th>
                                                <th>Qualification</th>
                                                <th>Experience</th>
                                                <th>Unit</th>
                                                <th>Slots</th>
                                                <th>Last Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1; @endphp
                                            @foreach ($jobs as $job)
                                            @if ($job->status === 'active' || Auth::user()->hasAnyRole(['hr', 'admin']))
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td>IJP - {{ $job->id }}</td>
                                                <td>{{ ucfirst($job->job_title) }}</td>
                                                <td>{{ $job->qualifications }}</td>
                                                <td class="text-center">{{ $job->work_experience }}</td>
                                                <td>{{ $job->unit }}</td>
                                                <td>{{ $job->slot_available }}</td>
                                                <td>{{ \Carbon\Carbon::parse($job->end_date)->format('d-m-Y') }}
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="btn btn-sm btn-primary fw-bold {{ $job->status === 'active' }}">
                                                        {{ ucfirst($job->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#offcanvasBottom{{ $job->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    @hasanyrole(['admin|hr'])
                                                    <a href="{{ route('internal-jobs.edit', $job->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    @endhasanyrole
                                                </td>
                                            </tr>
                                            @include('internal_jobs.show', ['job' => $job])
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="myhistory-tab-pane">
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="d-flex justify-content-end">
                                        <form method="GET" action="{{ route('export.applicants') }}">
                                            <div class="btn-group">
                                                {{-- <button type="button" class="btn btn-outline-primary dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    ⬇️ Export
                                                </button> --}}
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="bi bi-file-earmark-excel"></i> Download as
                                                            Excel
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="submit"
                                                            formaction="{{ route('export.applicants.pdf') }}"
                                                            class="dropdown-item text-danger">
                                                            <i class="bi bi-file-earmark-pdf"></i> Download as PDF
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="myappTable"
                                        class="table table-bordered table-light align-middle text-center w-100">

                                        <thead class="table-light align-middle text-center">
                                            <tr>
                                                <th style="width: 50px;">S.No</th>
                                                <th style="width: 100px;">Ijp Id</th>
                                                <th style="width: 200px;">Job Title</th>
                                                {{-- <th style="width: 180px;">Applicant</th> --}}
                                                <th style="width: 220px;">Email</th>
                                                <th style="width: 220px;">Status</th>
                                                <th style="width: 120px;">Resume</th>
                                            </tr>
                                            {{-- <tr class="bg-light">
                                                    <th></th>
                                                    <th>
                                                        <input type="text" placeholder="Search ID"
                                                            class="form-control form-control-sm w-100"
                                                            style="font-size: 13px;" />
                                                    </th>
                                                    <th>
                                                        <input type="text" placeholder="Search Title"
                                                            class="form-control form-control-sm w-100"
                                                            style="font-size: 13px;" />
                                                    </th>
                                                    <th>
                                                        <input type="text" placeholder="Search Name"
                                                            class="form-control form-control-sm w-100"
                                                            style="font-size: 13px;" />
                                                    </th>
                                                    <th>
                                                        <input type="text" placeholder="Search Email"
                                                            class="form-control form-control-sm w-100"
                                                            style="font-size: 13px;" />
                                                    </th>
                                                    <th></th>
                                                </tr> --}}
                                        </thead>
                                        <tbody>
                                            @php $counter = 1; @endphp
                                            {{-- @dd($applicants); --}}
                                            @foreach ($applicants as $applicant)
                                            @if ($applicant->employee_id === auth()->id())
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>IJP - {{ $applicant->job->id ?? '-' }}</td>
                                                <td class="text-primary fw-bold">
                                                    {{ ucfirst($applicant->job->job_title ?? '-') }}
                                                </td>
                                                {{-- <td>{{ $applicant->user->name ?? '-' }}</td> --}}
                                                <td>{{ $applicant->user->email ?? '-' }}</td>
                                                <td>
                                                    @if ($applicant->status == 'applied')
                                                    <span class="text-white btn btn-primary">Applied</span>
                                                    @elseif($applicant->status == 'selected' || $applicant->status == 'Selected')
                                                    <span
                                                        class="text-white btn btn-success">Selected</span>
                                                    @elseif($applicant->status == 'rejected' || $applicant->status == 'Rejected')
                                                    <span
                                                        class="text-white btn btn-danger ">Rejected</span>
                                                    @else
                                                    <span class="btn btn-light text-black">Something
                                                        went
                                                        Wrong</span>
                                                    @endif
                                                <td>
                                                    @if ($applicant->resume_path)
                                                    <button class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#resumeModal{{ $applicant->id }}">
                                                        <i class="bi bi-file-earmark-text"></i>
                                                    </button>

                                                    <div class="modal fade"
                                                        id="resumeModal{{ $applicant->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div
                                                            class="modal-dialog modal-xl modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header text-white"
                                                                    style="background: linear-gradient(90deg, #fc4a1a, #f7b733);">
                                                                    <h5 class="modal-title">
                                                                        Resume –
                                                                        {{ $applicant->user->name ?? '' }}
                                                                        - For the
                                                                        {{ $applicant->job->job_title ?? '' }}
                                                                        position
                                                                    </h5>
                                                                    <button type="button"
                                                                        class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body p-0">
                                                                    <iframe
                                                                        src="{{ asset('storage/' . $applicant->resume_path) }}"
                                                                        width="100%" height="600px"
                                                                        style="border: none;"></iframe>
                                                                </div>
                                                                <div
                                                                    class="modal-footer d-flex justify-content-end">
                                                                    <a href="{{ asset('storage/' . $applicant->resume_path) }}"
                                                                        class="btn btn-success" download>
                                                                        <i class="bi bi-download"></i>
                                                                        Download
                                                                        Resume
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <span class="text-muted">No Resume</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Job Applicants --}}
                            {{-- Job Applicants --}}
                            @hasanyrole(['hr', 'admin'])
                            <div class="tab-pane fade" id="applicants-tab-pane">
                                {{-- Filter + Export --}}
                                <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap">
                                    <form method="GET" action="{{ route('internal-jobs.index') }}"
                                        class="d-flex align-items-center gap-2">
                                        <select name="job_id" class="form-select" style="max-width:300px;">
                                            <option value="">-- All Jobs --</option>
                                            @foreach ($jobs as $job)
                                            <option value="{{ $job->id }}"
                                                {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                                IJP - {{ $job->id }} | {{ $job->job_title }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary">Search</button>
                                        <a href="{{ route('internal-jobs.index') }}"
                                            class="btn btn-outline-secondary">Reset</a>
                                    </form>

                                    <form method="GET" action="{{ route('export.applicants') }}">
                                        <input type="hidden" name="job_id" value="{{ request('job_id') }}">
                                        <button type="submit" class="btn btn-outline-success p-2">
                                            <i class="bi bi-arrow-down-circle"></i> Download as Excel
                                        </button>
                                    </form>
                                </div>

                                {{-- Applicants Table --}}
                                <div class="table-responsive">
                                    <table id="applicantsTable"
                                        class="table table-bordered table-light align-middle text-center w-100">
                                        <thead class="table-light align-middle text-center">
                                            <tr>
                                                <th>S.No</th>
                                                <th>IJP ID</th>
                                                <th>Job Title</th>
                                                <th>Applicant</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Resume</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter = 1; @endphp
                                            @foreach ($applicants as $applicant)
                                            @if ($applicant->status == 'applied')
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>IJP - {{ $applicant->job->id ?? '-' }}</td>
                                                <td>{{ ucfirst($applicant->job->job_title ?? '-') }}</td>
                                                <td>{{ $applicant->user->name ?? '-' }}</td>
                                                <td>{{ $applicant->user->email ?? '-' }}</td>
                                                <td>
                                                    @if ($applicant->status == 'applied')
                                                    <span class="text-white btn btn-primary">Applied</span>
                                                    @elseif(strtolower($applicant->status) == 'selected')
                                                    <span
                                                        class="text-white btn btn-success">Selected</span>
                                                    @elseif(strtolower($applicant->status) == 'rejected')
                                                    <span class="text-white btn btn-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($applicant->resume_path)
                                                    <a href="{{ asset('storage/' . $applicant->resume_path) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-file-earmark-text"></i> View
                                                    </a>
                                                    @else
                                                    <span class="text-muted">No Resume</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- @endhasanyrole --}}

                            {{-- Final Job Status Results Tab --}}
                            <div class="tab-pane fade" id="results-tab-pane">
                                <div class="table-responsive">
                                    <form action="{{ route('import.applicants.pdf') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex justify-content-end">
                                            <div class="d-flex flex-wrap gap-2">
                                                <label for="excel_file" class="form-label text-danger mb-0">
                                                    <strong>Upload Job Results (Excel):</strong>
                                                </label>

                                                <input type="file" name="excel_file"
                                                    class="form-control form-control-sm" style="max-width: 220px;"
                                                    required>

                                                <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <br>
                                <table id="finalTable"
                                    class="table table-bordered table-hover align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>IJP ID</th>
                                            <th>Job Title</th>
                                            <th>Applicant</th>
                                            <th>Email</th>

                                            {{-- <th>Qualifications</th> --}}
                                            {{-- <th>Experience</th> --}}
                                            <th>Interview Date</th>
                                            <th>Interview Panel</th>
                                            <th>Status</th>
                                            {{-- <th>Result</th> --}}
                                            <th>Joining Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dump($results); --}}
                                        @php $count = 1; @endphp
                                        @foreach ($results as $result)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>IJP - {{ $result->ijp_id }}</td>
                                            <td>{{ ucfirst($result->job_title) }}</td>
                                            <td>{{ $result->applicant }}</td>
                                            <td>{{ $result->email }}</td>
                                            <td>{{ $result->interview_date ? \Carbon\Carbon::parse($result->joining_date)->format('d-m-Y') : '-NA-' }}
                                            </td>
                                            {{-- <td>{{ $result->qualifications }}</td> --}}
                                            {{-- <td>{{ $result->experience }}</td> --}}
                                            <td>{{ $result->interview_panel ? ucfirst($result->interview_panel) : 'NA' }}
                                            </td>

                                            <td>
                                                @if ($result->status == 'applied')
                                                <span class="text-white btn btn-primary">Applied</span>
                                                @elseif($result->status == '' || $result->status == '')
                                                <span class="btn btn-light text-black">Something went
                                                    Wrong</span>
                                                @elseif($result->status == 'selected' || $result->status == 'Selected')
                                                <span class="text-white btn btn-success">Selected</span>
                                                @elseif($result->status == 'rejected' || $result->status == 'Rejected')
                                                <span class="text-white btn btn-danger ">Rejected</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ $result->interview_result }}</td> --}}

                                            <td>
                                                {{ $result->joining_date ? \Carbon\Carbon::parse($result->joining_date)->format('d-m-Y') : '-NA-' }}
                                            </td>


                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endhasanyrole
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
</x-app-layout>