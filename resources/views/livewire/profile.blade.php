<div>
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Registered Drugs</h2>
        <div class="flex gap-2">
            <a href="{{ route('export.drugs') }}" class="bg-green-500 text-white rounded p-2">Export to CSV</a>
            <a href="{{ route('drug-search') }}" class="bg-blue-500 text-white rounded p-2">Back to Drug Search</a>
            <button wire:click="deleteAll" class="bg-red-700 text-white rounded p-2" onclick="confirm('Are you sure you want to delete all?') || event.stopImmediatePropagation()">Delete All</button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-500 text-white p-2 mb-4 rounded">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-500 text-white p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <table class="min-w-full border-collapse border border-gray-200">
        <thead>
            <tr>
                <th class="border border-gray-300 p-2">User ID</th>
                <th class="border border-gray-300 p-2">NDC Code</th>
                <th class="border border-gray-300 p-2">Brand Name</th>
                <th class="border border-gray-300 p-2">Generic Name</th>
                <th class="border border-gray-300 p-2">Labeler Name</th>
                <th class="border border-gray-300 p-2">Product Type</th>
                <th class="border border-gray-300 p-2">Source</th>
                <th class="border border-gray-300 p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registeredDrugs as $drug)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $drug->user_id }}</td>
                    <td class="border border-gray-300 p-2">{{ $drug->ndc_code }}</td>
                    <td class="border border-gray-300 p-2">{{ $drug->brand_name }}</td>
                    <td class="border border-gray-300 p-2">{{ $drug->generic_name }}</td>
                    <td class="border border-gray-300 p-2">{{ $drug->labeler_name }}</td>
                    <td class="border border-gray-300 p-2">{{ $drug->product_type }}</td>
                    <td class="border border-gray-300 p-2">{{ $drug->source }}</td>
                    <td class="border border-gray-300 p-2">
                        <button wire:click="deleteRegisteredDrug({{ $drug->id }})" class="bg-red-500 text-white rounded p-1">
                            Delete
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="border border-gray-300 p-2 text-center">No registered drugs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $registeredDrugs->links() }}
    </div>
</div>
