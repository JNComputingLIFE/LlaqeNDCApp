<?php
// app/Http/Livewire/Profile.php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserDrug;

class Profile extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; // Optional: use Tailwind pagination styling

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }
    
    public function render()
    {
        return view('livewire.profile', [
            'registeredDrugs' => UserDrug::where('user_id', auth()->id())
                                        ->paginate(10, ['*'], 'page', $this->getPage()),
        ]);
    }

    public function deleteRegisteredDrug($id)
    {
        $drug = UserDrug::where('id', $id)->where('user_id', auth()->id())->first();

        if ($drug) {
            $drug->delete();
            session()->flash('message', 'Drug deleted successfully.');

            // Get current page with this helper:
            $currentPage = $this->page ?? 1; // <-- But $this->page is protected, so this doesn't work

            // Instead, use this helper method:
            $currentPage = $this->getPage();

            $drugs = UserDrug::where('user_id', auth()->id())->paginate(10, ['*'], 'page', $currentPage);

            if ($drugs->isEmpty() && $currentPage > 1) {
                $this->previousPage();
            }
        } else {
            session()->flash('error', 'Drug not found.');
        }
    }

    public function deleteAll()
    {
        UserDrug::where('user_id', auth()->id())->delete();

        session()->flash('message', 'All registered drugs deleted successfully.');

        $this->resetPage();
    }
}
