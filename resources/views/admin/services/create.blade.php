<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Add Service</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-5xl">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" id="service-form">
                    @csrf
                    @include('admin.services._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
