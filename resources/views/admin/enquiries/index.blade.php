<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Enquiry Management</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">View Enquiries</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Browse all incoming enquiries with search, sorting, and pagination.</p>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table id="enquiriesTable" class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                        <thead>
                            <tr class="text-left text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Email</th>
                                <th class="px-4 py-3">Phone</th>
                                <th class="px-4 py-3">Subject</th>
                                <th class="px-4 py-3">Source</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Received</th>
                                <th class="px-4 py-3">Message</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.datatable-assets')
    <script>
        $(function () {
            $('#enquiriesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.enquiries.index') }}',
                order: [[6, 'desc']],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'subject', name: 'subject' },
                    { data: 'source', name: 'source' },
                    { data: 'status', name: 'status' },
                    { data: 'received_at', name: 'received_at' },
                    { data: 'message', name: 'message' },
                ],
                columnDefs: [
                    {
                        targets: [5],
                        orderable: true,
                        searchable: true,
                    }
                ],
            });
        });
    </script>
</x-app-layout>
