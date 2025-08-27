<div class="table-responsive">
    <table id="applicantsTable" class="table table-bordered table-light align-middle text-center w-100">
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
                            <span class="text-white btn btn-success">Selected</span>
                        @elseif(strtolower($applicant->status) == 'rejected')
                            <span class="text-white btn btn-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if ($applicant->resume_path)
                            <a href="{{ asset('storage/' . $applicant->resume_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-file-earmark-text"></i> View
                            </a>
                        @else
                            <span class="text-muted">No Resume</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
