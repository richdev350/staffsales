<?php
namespace database\seeds\CarbonOnlyCsvSeeder;

use Carbon\Carbon;
use Flynsarmy\CsvSeeder\CsvSeeder;

class CarbonOnlyCsvSeeder extends CsvSeeder
{
    public function run()
    {
        // Cache created_at and updated_at if we need to
        if ($this->timestamps) {
            if (!$this->created_at) {
                $this->created_at = Carbon::now();
            }
            if (!$this->updated_at) {
                $this->updated_at = Carbon::now();
            }
        }

        $this->seedFromCSV($this->filename, $this->csv_delimiter);
    }
}
