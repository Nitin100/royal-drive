<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Booking Management</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Bookings</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Browse all bookings with search, sorting, and pagination.</p>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table id="bookingsTable" class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Contact</th>
                                <th class="px-4 py-3">Pickup</th>
                                <th class="px-4 py-3">Drop-off</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Time</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Vehicle</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Flight</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="bookingStatusToast" class="pointer-events-none fixed bottom-4 right-4 z-50 hidden max-w-sm rounded-xl border border-emerald-500/30 bg-emerald-600 px-4 py-3 text-sm font-medium text-white shadow-2xl">
        <div class="flex items-center gap-3">
            <div id="bookingStatusToastBody" class="flex-1">Booking status updated successfully.</div>
            <button type="button" id="bookingStatusToastClose" class="rounded-md p-1 text-white/80 transition hover:bg-white/10 hover:text-white" aria-label="Close notification">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                    <path d="M5 5l10 10M15 5 5 15" />
                </svg>
            </button>
        </div>
    </div>

    @include('admin.partials.datatable-assets')
    <script>
        $(function () {
            const bookingsTable = $('#bookingsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.bookings.index') }}',
                order: [[5, 'desc']],
                pageLength: 10,
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'contact', name: 'contact' },
                    { data: 'pickup_location', name: 'pickup_location', orderable: false },
                    { data: 'dropoff_location', name: 'dropoff_location', orderable: false },
                    { data: 'pickup_date', name: 'pickup_date' },
                    { data: 'pickup_time', name: 'pickup_time' },
                    { data: 'status', name: 'status' },
                    { data: 'fleet', name: 'fleet', orderable: false },
                    { data: 'service', name: 'service', orderable: false },
                    { data: 'flight_number', name: 'flight_number' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                ],
            });

            const statusOptions = @json($statusOptions);
            const bookingModal = document.getElementById('bookingStatusModal');
            const modalTitle = document.getElementById('bookingStatusModalLabel');
            const modalBookingId = document.getElementById('bookingStatusBookingId');
            const modalBookingStatus = document.getElementById('bookingStatusValue');
            const modalForm = document.getElementById('bookingStatusForm');
            const toastElement = document.getElementById('bookingStatusToast');
            const toastBody = document.getElementById('bookingStatusToastBody');
            const toastClose = document.getElementById('bookingStatusToastClose');
            const modalBackdrop = document.getElementById('bookingStatusModalBackdrop');
            const modalPanel = document.getElementById('bookingStatusModalPanel');
            const modalCloseButtons = document.querySelectorAll('[data-booking-status-modal-close]');
            let toastTimer = null;

            const showToast = function (message, type = 'success') {
                if (!toastElement || !toastBody) {
                    return;
                }

                toastElement.classList.remove('hidden', 'bg-emerald-600', 'bg-rose-600', 'border-emerald-500/30', 'border-rose-500/30');
                toastElement.classList.add(type === 'success' ? 'bg-emerald-600' : 'bg-rose-600');
                toastElement.classList.add(type === 'success' ? 'border-emerald-500/30' : 'border-rose-500/30');
                toastBody.textContent = message;

                if (toastTimer) {
                    clearTimeout(toastTimer);
                }

                toastTimer = window.setTimeout(function () {
                    toastElement.classList.add('hidden');
                }, 2500);
            };

            const openModal = function () {
                if (!bookingModal || !modalPanel) {
                    return;
                }

                bookingModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');

                window.requestAnimationFrame(function () {
                    modalPanel.classList.remove('scale-95', 'opacity-0');
                    modalPanel.classList.add('scale-100', 'opacity-100');
                });
            };

            const closeModal = function () {
                if (!bookingModal || !modalPanel) {
                    return;
                }

                modalPanel.classList.remove('scale-100', 'opacity-100');
                modalPanel.classList.add('scale-95', 'opacity-0');

                window.setTimeout(function () {
                    bookingModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }, 150);
            };

            if (bookingModal && modalTitle && modalBookingId && modalBookingStatus && modalForm) {
                $('#bookingsTable').on('click', '[data-booking-status-trigger="true"]', function () {
                    const bookingId = this.getAttribute('data-booking-id');
                    const bookingLabel = this.getAttribute('data-booking-label') || 'Booking';
                    const currentStatus = this.getAttribute('data-current-status') || 'pending';

                    modalTitle.textContent = 'Update Booking Status';
                    modalBookingId.value = bookingId;
                    modalForm.action = '{{ url('admin/bookings') }}/' + bookingId + '/status';
                    modalBookingStatus.value = currentStatus;

                    const statusLabel = document.getElementById('bookingStatusBookingLabel');
                    if (statusLabel) {
                        statusLabel.textContent = bookingLabel;
                    }

                    openModal();
                });

                if (modalBackdrop) {
                    modalBackdrop.addEventListener('click', closeModal);
                }

                modalCloseButtons.forEach(function (button) {
                    button.addEventListener('click', closeModal);
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && !bookingModal.classList.contains('hidden')) {
                        closeModal();
                    }
                });

                modalForm.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const submitButton = modalForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton ? submitButton.innerHTML : '';

                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = 'Updating...';
                    }

                    try {
                        const response = await fetch(modalForm.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: new FormData(modalForm),
                        });

                        const payload = await response.json();

                        if (!response.ok) {
                            throw payload;
                        }

                        showToast(payload.message || 'Booking status updated successfully.', 'success');

                        closeModal();

                        bookingsTable.ajax.reload(null, false);
                    } catch (error) {
                        const message = error?.message || (error?.errors ? Object.values(error.errors).flat().join(' ') : 'Unable to update booking status.');
                        showToast(message, 'danger');
                    } finally {
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                        }
                    }
                });
            }
        });
    </script>

    <div id="bookingStatusModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="bookingStatusModalLabel" aria-modal="true" role="dialog">
        <div class="flex min-h-full items-center justify-center px-4 py-8 text-center sm:block sm:p-0">
            <div id="bookingStatusModalBackdrop" class="fixed inset-0 bg-gray-950/80 backdrop-blur-[1px] transition-opacity"></div>

            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            <div id="bookingStatusModalPanel" class="relative inline-block w-3/4 max-w-md transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-2xl transition-all duration-150 ease-out dark:bg-gray-800 sm:my-8 sm:align-middle scale-95 opacity-0">
                <form id="bookingStatusForm" method="POST" action="{{ route('admin.bookings.status', 0) }}">
                    @csrf
                    @method('PATCH')
                    <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                        <div class="flex items-center justify-between gap-4">
                            <h5 class="text-base font-semibold text-gray-900 dark:text-gray-100" id="bookingStatusModalLabel">Update Booking Status</h5>
                            <button type="button" data-booking-status-modal-close class="rounded-md p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100" aria-label="Close dialog">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                    <path d="M5 5l10 10M15 5 5 15" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4 px-4 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Booking: <span id="bookingStatusBookingLabel" class="font-semibold text-gray-900 dark:text-gray-100"></span></p>
                        <input type="hidden" id="bookingStatusBookingId" name="booking_id" value="">

                        <div>
                            <label for="bookingStatusValue" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-200">Booking Status</label>
                            <select id="bookingStatusValue" name="status" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" required>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-4 py-3 dark:border-gray-700">
                        <button type="button" data-booking-status-modal-close class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">Cancel</button>
                        <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200">Confirm Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
