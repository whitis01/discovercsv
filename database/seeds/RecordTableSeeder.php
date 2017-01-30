<?php

use App\Record;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class RecordTableSeeder extends Seeder
{
    const RECORD_KEYS = ['trans_date', 'post_date', 'description', 'amount', 'category'];
    /**
     * Run the database seeds.
     *
     * Load up the records from the last two years, or whatever is in the .csv file.
     *
     * @return void
     */
    public function run()
    {
        $content = File::get(base_path().'/public/storage/discover.csv');
        $records = explode("\r\n", $content);

        array_pop($records);
        array_forget($records, 0);

        DB::table('records')->delete();

        foreach ($records as $record) {
            try {
                $rec = explode(',', $record);
                $rec[0] = new Datetime($rec[0]);
                $rec[1] = new Datetime($rec[1]);
                $rec = array_combine(self::RECORD_KEYS, $rec);
            } catch (Exception $e) {
                $rec[2] = $rec[2].$rec[3];
                array_forget($rec, 3);
                $rec = array_combine(self::RECORD_KEYS, $rec);
            }

            $rec = new Record($rec);
            $rec->save();
        }
    }
}
