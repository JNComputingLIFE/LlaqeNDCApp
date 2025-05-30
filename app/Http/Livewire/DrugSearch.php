<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\DrugSearchResult;
use App\Models\UserDrug;

class DrugSearch extends Component
{

    public $paginatedResults;
    public $searchInput;
    public $results = [];
    public $perPage = 10;
    public $page = 1;

    // Selection states
    public $selected = [];
    public $selectAll = false;

    //before search
    public $loading = false;

    public function search()
    {
        \Log::info('Search started');
        $this->loading = true;
        $this->page = 1;
    
        $ndcCodes = collect(explode(',', $this->searchInput))
            ->map(fn($code) => trim($code))
            ->filter(fn($code) => !empty($code))
            ->unique()
            ->values()
            ->all();
    
        if (empty($ndcCodes)) {
            $this->loading = false;
            return;
        }
    
        $foundDrugs = DrugSearchResult::whereIn('ndc_code', $ndcCodes)->get()->keyBy('ndc_code');
        $missingCodes = array_filter($ndcCodes, fn($code) => !isset($foundDrugs[$code]));
    
        if (!empty($missingCodes)) {
            $queryParts = array_map(fn($code) => 'product_ndc:"' . $code . '"', $missingCodes);
            $query = implode('+', $queryParts);
            $apiUrl = 'https://api.fda.gov/drug/ndc.json?search=' . $query . '&limit=100';
    
            try {
                $response = Http::timeout(10)->get($apiUrl); // set timeout to avoid hanging
                $data = $response->json();
    
                if (isset($data['results'])) {
                    foreach ($data['results'] as $item) {
                        $drugData = [
                            'ndc_code'     => $item['product_ndc'] ?? null,
                            'brand_name'   => $item['brand_name'] ?? '---',
                            'generic_name' => $item['generic_name'] ?? '---',
                            'labeler_name' => $item['labeler_name'] ?? '---',
                            'product_type' => $item['product_type'] ?? '---',
                            'source'       => 'OpenFDA',
                        ];
    
                        $drug = DrugSearchResult::updateOrCreate(
                            ['ndc_code' => $drugData['ndc_code']],
                            $drugData
                        );
    
                        $foundDrugs[$drugData['ndc_code']] = $drug;
                    }
                }
            } catch (\Exception $e) {
                \Log::error('OpenFDA API error: ' . $e->getMessage());
                // You might want to show a user-friendly error here
            }
        }
    
        $results = collect($foundDrugs);
        $stillMissing = array_filter($ndcCodes, fn($code) => !isset($foundDrugs[$code]));
    
        foreach ($stillMissing as $missingCode) {
            $results->push((object)[
                'id'           => 0,
                'ndc_code'     => $missingCode,
                'brand_name'   => '---',
                'generic_name' => '---',
                'labeler_name' => '---',
                'product_type' => '---',
                'source'       => 'not found',
            ]);
        }
    
        $this->paginatedResults = $results->chunk($this->perPage);
        $this->results = collect($this->paginatedResults->get($this->page - 1) ?? []);
    
        $this->loading = false;
        \Log::info('Search finished');
    }
    

    public function registerSelected()
    {
        if (empty($this->selected)) {
            session()->flash('error', 'No drugs selected.');
            return;
        }

        $drugs = DrugSearchResult::whereIn('id', $this->selected)->get();

        foreach ($drugs as $drug) {
            UserDrug::updateOrCreate([
                'user_id' => Auth::id(),
                'ndc_code' => $drug->ndc_code,
            ], [
                'brand_name'   => $drug->brand_name,
                'generic_name' => $drug->generic_name,
                'labeler_name' => $drug->labeler_name,
                'product_type' => $drug->product_type,
                'source'       => $drug->source,
            ]);
        }

        $this->selected = [];
        $this->selectAll = false;

        session()->flash('message', 'Selected drugs registered successfully.');
        return redirect()->route('profile');
    }

    public function setPage($page)
    {
        $this->page = $page;
        if ($this->paginatedResults) {
            $this->results = collect($this->paginatedResults->get($this->page - 1) ?? []);
        }
    }
    

    public function render()
    {
        return view('livewire.drug-search', [
            'results' => $this->results,
            'paginatedResults' => $this->paginatedResults,
        ]);
    }

    public function registerAllOnPage()
{
    foreach ($this->results as $drug) {
        // Avoid duplicate registration
        $exists = UserDrug::where('user_id', Auth::id())
            ->where('ndc_code', $drug['ndc_code'])
            ->exists();

        if (!$exists) {
            UserDrug::create([
                'user_id'      => Auth::id(),
                'ndc_code'     => $drug['ndc_code'],
                'brand_name'   => $drug['brand_name'],
                'generic_name' => $drug['generic_name'] ?? '---',
                'labeler_name' => $drug['labeler_name'] ?? '---',
                'product_type' => $drug['product_type'] ?? '---',
                'source'       => $drug['source'] ?? '---',
            ]);
        }
    }

    session()->flash('message', 'All drugs on the current page registered successfully.');
    return redirect()->route('profile');
}

}
