<!-- resources/views/livewire/login-form.blade.php -->

<form wire:submit.prevent="login">
    @csrf
    <input type="email" wire:model="email" required placeholder="Email" />
    <input type="password" wire:model="password" required placeholder="Password" />
    <button type="submit">Login</button>

    @if (session()->has('error'))
        <div>{{ session('error') }}</div>
    @endif
</form>
