<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Add CMS Page</h2>
    </x-slot>

    <div class="p-6 lg:p-8">
        <div class="mx-auto max-w-4xl">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.pages._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
