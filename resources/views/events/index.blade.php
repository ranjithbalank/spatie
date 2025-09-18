<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full mb-4">
            <h2 class="font-semibold text-xl text-black-800 leading-tight">
                Calendar
                <!-- <a href="https://drive.google.com/file/d/1qeOU-CTgYXY89osChFdAgawN0JfPiAP3/view?usp=sharing"
                    target="_blank" class="absolute right-0 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 m-2" viewBox="0 0 20 20" fill="red">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-2 4a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 00-1-1h-2a1 1 0 00-1 1z"
                            clip-rule="evenodd" />
                    </svg>
                </a> -->
            </h2>
            <a href="#" class="text-sm text-red-700 no-underline"
                onclick="window.history.back(); return false;">&larr; Back</a>
        </div>
        <hr class="mb-4">

        <style>
            #calendar {
                min-height: 100%;
                background: #fff;
                padding: 20px;
                border-radius: 15px;
            }
        </style>

        <div class="container mx-auto">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card bg-white rounded-xl shadow-lg">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-start" style="width: 50%" tabindex="-1" id="addEventOffcanvas">
            <div class="offcanvas-header text-white" style="background: linear-gradient(90deg, #fc4a1a, #f7b733);">
                <h5 class="offcanvas-title">Add Event</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <form id="addEventForm">
                    @csrf
                    <label>Title</label>
                    <input type="text" name="title" class="form-control mb-2" required>
                    <label>Description</label>
                    <textarea name="description" class="form-control mb-2"></textarea>
                    <label>Start Date & Time</label>
                    <input type="datetime-local" name="start_date" id="start_date" class="form-control mb-2" required>
                    <label>End Date & Time</label>
                    <input type="datetime-local" name="end_date" id="end_date" class="form-control mb-2" required>
                    <label>Color</label>
                    <input type="color" name="color" value="#3788d8" class="form-control form-control-color mb-3">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-secondary me-2" type="button"
                            data-bs-dismiss="offcanvas">Cancel</button>
                        <button class="btn btn-success" type="submit">Create</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="editEventModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" id="editEventForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header text-white"
                            style="background: linear-gradient(90deg, #fc4a1a, #f7b733);">
                            <h5 class="modal-title">Edit Event</h5>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <label>Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control mb-2" required>
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control mb-2"></textarea>
                            <label>Start Date & Time</label>
                            <input type="datetime-local" name="start_date" id="edit_start_date"
                                class="form-control mb-2" required>
                            <label>End Date & Time</label>
                            <input type="datetime-local" name="end_date" id="edit_end_date" class="form-control mb-2"
                                required>
                            <label>Color</label>
                            <input type="color" name="color" id="edit_color"
                                class="form-control form-control-color mb-2">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" id="deleteEventBtn">Delete</button>
                            <button class="btn btn-success" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="dailyEventModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-white"
                        style="background: linear-gradient(90deg, #fc4a1a, #f7b733);">
                        <h5 class="modal-title">Events on <span id="eventDateTitle"></span></h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="table-white">
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody id="dailyEventTable"></tbody>
                        </table>

                        {{-- The button wrapper is now controlled by Blade's @can directive --}}
                        <div class="mt-3 text-end" id="createEventBtnWrapper">
                            @can('create circulars')
                            <button class="btn btn-primary" id="createEventFromDailyBtn">
                                + Create Event
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

        @php
        $roles = auth()->user()->getRoleNames();
        $permissions = auth()->user()->getAllPermissions()->pluck('name');
        @endphp

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const userRoles = @json($roles);
                const userPermissions = @json($permissions);

                function hasRole(role) {
                    return userRoles.includes(role);
                }

                function hasPermission(permission) {
                    return userPermissions.includes(permission);
                }

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: '/events-data',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    dateClick: function(info) {
                        loadDailyEvents(info.dateStr);
                    },
                    eventClick: function(info) {
                        const event = info.event;
                        // Use consistent permission name: 'create circulars' or 'edit events'
                        if (hasRole('admin') || hasRole('hr') || hasPermission('edit events')) {
                            document.getElementById('edit_id').value = event.id;
                            document.getElementById('edit_title').value = event.title;
                            document.getElementById('edit_description').value = event.extendedProps
                                .description || '';
                            document.getElementById('edit_color').value = event.backgroundColor;

                            const offset = (d) => new Date(d.getTime() - (d.getTimezoneOffset() * 60000))
                                .toISOString().slice(0, 16);
                            document.getElementById('edit_start_date').value = offset(new Date(event
                                .start));
                            document.getElementById('edit_end_date').value = offset(event.end ? new Date(
                                event.end) : new Date(event.start));

                            document.getElementById('editEventForm').action = `/events/${event.id}`;
                            new bootstrap.Modal(document.getElementById('editEventModal')).show();
                        } else {
                            // Non-admin/HR users can still view events
                            loadDailyEvents(event.start.toISOString().slice(0, 10));
                        }
                    }
                });

                calendar.render();

                function loadDailyEvents(dateStr) {
                    fetch(`/events/daily/${dateStr}`)
                        .then(res => res.json())
                        .then(events => {
                            const tbody = document.getElementById('dailyEventTable');
                            document.getElementById('eventDateTitle').innerText = dateStr;

                            if (events.length === 0) {
                                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No events</td></tr>';
                            } else {
                                tbody.innerHTML = events.map(e => {
                                    const startDate = new Date(e.start);
                                    const endDate = new Date(e.end);
                                    const startTime = startDate.toLocaleTimeString([], {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                    const endTime = endDate.toLocaleTimeString([], {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                    return `
                                        <tr>
                                            <td>${e.title}</td>
                                            <td>${e.description || 'N/A'}</td>
                                            <td>${startTime}</td>
                                            <td>${endTime}</td>
                                        </tr>`;
                                }).join('');
                            }

                            // Only set the onclick for the button if it exists
                            const createBtn = document.getElementById('createEventFromDailyBtn');
                            if (createBtn) {
                                createBtn.onclick = function() {
                                    const localDate = new Date(dateStr + 'T00:00:00');
                                    const offsetDate = new Date(localDate.getTime() - (localDate
                                        .getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
                                    document.getElementById('start_date').value = offsetDate;
                                    document.getElementById('end_date').value = offsetDate;
                                    bootstrap.Modal.getInstance(document.getElementById('dailyEventModal'))
                                        .hide();
                                    new bootstrap.Offcanvas(document.getElementById('addEventOffcanvas'))
                                        .show();
                                };
                            }

                            new bootstrap.Modal(document.getElementById('dailyEventModal')).show();
                        })
                        .catch(error => console.error('Error loading daily events:', error));
                }

                // ✅ Add Event Form (AJAX save to DB)
                document.getElementById('addEventForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch("{{ route('events.store') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(event => {

                            // Close the offcanvas first
                            if (offcanvasInstance) {
                                offcanvasInstance.hide();
                            }

                            calendar.addEvent({
                                id: event.id,
                                title: event.title,
                                start: event.start,
                                end: event.end,
                                backgroundColor: event.color,
                                extendedProps: {
                                    description: event.description
                                }
                            });
                            // Reset the form
                            document.getElementById('addEventForm').reset();
                            // Reload daily events to reflect the new addition 
                            loadDailyEvents(event.start.slice(0, 10));
                        })
                        .catch(error => console.error('Error creating event:', error));
                });

                // ✅ Delete Event
                if (hasRole('admin') || hasRole('hr') || hasPermission('delete events')) {
                    document.getElementById('deleteEventBtn')?.addEventListener('click', function() {
                        const eventId = document.getElementById('edit_id').value;
                        if (confirm('Are you sure you want to delete this event?')) {
                            fetch(`/events/${eventId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            }).then(response => {
                                if (response.ok) {
                                    const event = calendar.getEventById(eventId);
                                    if (event) event.remove();
                                    bootstrap.Modal.getInstance(document.getElementById(
                                        'editEventModal')).hide();
                                } else {
                                    console.error('Error deleting event.');
                                }
                            });
                        }
                    });
                }
            });
        </script>
        @endpush


    </x-slot>
</x-app-layout>