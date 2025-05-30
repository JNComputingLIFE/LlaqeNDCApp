<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/ExportController.php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserDrug;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportUserDrugs()
    {
        $userId = Auth::id();

        $drugs = UserDrug::where('user_id', $userId)->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=registered_drugs.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['User ID', 'NDC Code', 'Brand Name', 'Generic Name', 'Labeler Name', 'Product Type', 'Source'];

        $callback = function () use ($drugs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($drugs as $drug) {
                fputcsv($file, [
                    $drug->user_id,
                    $drug->ndc_code,
                    $drug->brand_name,
                    $drug->generic_name,
                    $drug->labeler_name,
                    $drug->product_type,
                    $drug->source,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
