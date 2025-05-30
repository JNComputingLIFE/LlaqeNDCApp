<x-guest-layout>
    <x-slot name="slot">
        <!-- Your content here -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Form fields -->
        </form>
    </x-slot>
</x-guest-layout>