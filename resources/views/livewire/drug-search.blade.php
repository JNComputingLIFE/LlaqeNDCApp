<div class="drug-search-wrapper">
  <img src="{{ asset('tenton.png') }}" alt="Logo" class="top-left-logo">
    @once
        @push('styles')
            <link rel="stylesheet" href="{{ asset('css/drug-search.css') }}">
        @endpush
    @endonce

    <div class="page-wrapper">
        <div class="container">
            <h1>Drug Search</h1>

            @if (session()->has('message'))
                <div class="message success">{{ session('message') }}</div>
            @endif

            @if (session()->has('error'))
                <div class="message error">{{ session('error') }}</div>
            @endif

            <div class="search-group">
                <input type="text" wire:model.defer="searchInput" placeholder="Enter NDC codes" />
                <button wire:click="search" wire:loading.attr="disabled">Search</button>
            </div>

            <!-- Loading spinner (automatically shows during search call) -->
        @if ($loading)
    <div class="loading-overlay">
        <div class="spinner"></div>
        <span>Loading...</span>
    </div>
        @endif

            @if (is_array($results) ? count($results) > 0 : $results->isNotEmpty())
                <div class="buttons-row">
                    <button wire:click="registerSelected" wire:loading.attr="disabled">Register Selected</button>
                    <button wire:click="registerAllOnPage" class="register-all" wire:loading.attr="disabled">Register All</button>
                </div>
            @endif

            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>NDC Code</th>
                        <th>Brand Name</th>
                        <th>Labeler Name</th>
                        <th>Product Type</th>
                        <th>Source</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($results as $result)
                        <tr>
                            <td>
                                @if($result->id != 0)
                                    <input type="checkbox" wire:model="selected" value="{{ $result->id }}">
                                @endif
                            </td>
                            <td>{{ $result->ndc_code }}</td>
                            <td>{{ $result->brand_name ?? '---' }}</td>
                            <td>{{ $result->labeler_name ?? '---' }}</td>
                            <td>{{ $result->product_type ?? '---' }}</td>
                            <td>{{ $result->source }}</td>
                            <td>
                                @if($result->id != 0)
                                    <button wire:click="registerDrug({{ $result->id }})" class="register-btn" wire:loading.attr="disabled">Register</button>
                                @else
                                    <button disabled class="register-btn" title="Drug not found">Register</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No results found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                @if ($paginatedResults)
                    @for ($i = 1; $i <= $paginatedResults->count(); $i++)
                        <a href="#"
                           wire:click.prevent="setPage({{ $i }})"
                           class="{{ $i == $page ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor
                @endif
            </div>
        </div>
    </div>
</div>
