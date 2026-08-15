<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Edit Service</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-5xl">
            <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                Editing: <span class="font-medium text-gray-700 dark:text-gray-200">{{ $service->title }}</span>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" id="service-form">
                    @csrf
                    @method('PUT')
                    @include('admin.services._form', ['service' => $service])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
