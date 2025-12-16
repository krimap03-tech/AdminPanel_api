<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class testing extends Command
{
    protected $signature = 'csv:process-phone {input} {phoneColumn}';
    protected $description = 'Extract or detect ISD code and clean phone numbers';

    public function handle()
    {
        $input = $this->argument('input');
        $phoneColumn = $this->argument('phoneColumn');

        if (!file_exists($input)) {
            $this->error("Input file not found: $input");
            return;
        }

        $output = storage_path('app/output/processed_' . date('Y_m_d_His') . '.csv');

        $in = fopen($input, 'r');
        $out = fopen($output, 'w');

        $headers = fgetcsv($in);

        if (!$headers) {
            $this->error("Invalid or empty CSV file.");
            return;
        }

        if (!in_array($phoneColumn, $headers)) {
            $this->error("Column '$phoneColumn' not found.");
            return;
        }

        $index = array_search($phoneColumn, $headers);
        $headers[] = 'isd_code';

        fputcsv($out, $headers);

        while (($row = fgetcsv($in)) !== false) {

            $raw = trim($row[$index]);

            // Clean digits only
            $digits = preg_replace('/\D/', '', $raw);
            $digits = ltrim($digits, '0');


            $isd = "";
            $local = $digits;

            // CASE 1 — If number already has ISD (+27 or +26)
            if (preg_match('/^\+?(27|26)/', $raw, $match)) {

                $isd = $match[1];

                // remove the ISD from local number
                if (str_starts_with($digits, $isd)) {
                    $local = substr($digits, strlen($isd));
                }
            }

            // CASE 2 — No + code → detect automatically
            else {
                $len = strlen($digits);
                Log::info('============================================');
                Log::info('$len===' .$len);
                Log::info('$digits===' .$digits);

                if ($len <= 9) {
                    // South Africa
                    $isd = "27";
                    $local = $digits;
                } 
                else if ($len >= 10) {
                    // Zambia
                    $isd = "26";
                    $local = $digits;
                } 
                
                else {
                    // invalid/unknown
                    $isd = "";
                    $local = $digits;
                }
                Log::info('$isd===' .$isd);
            }

            // Save cleaned number + ISD
            $row[$index] = $local;
            $row[] = $isd;

            fputcsv($out, $row);
        }

        fclose($in);
        fclose($out);

        $this->info("Processed file created:");
        $this->info($output);
    }
}


// php artisan csv:process-folder input