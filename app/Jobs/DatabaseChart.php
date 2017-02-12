<?php

namespace App\Jobs;

use ConsoleTVs\Charts\Builder\Database;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DatabaseChart extends Database
{
    /**
     * DatabaseChart constructor.
     * @param \Illuminate\Database\Eloquent\Collection|static[] $data
     * @param null $type
     * @param null $library
     */
    public function __construct($data, $type = null, $library = null)
    {
        parent::__construct($data, $type, $library);
    }


    /**
     * Group the data monthly based on the creation date.
     *
     * @param int  $year
     * @param bool $fancy
     *
     * @return Database
     */
    public function groupByMonth($year = null, $fancy = false)
    {
        $labels = [];
        $values = [];

        $date_column = $this->date_column;

        $year = $year ? $year : date('Y');

        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $month = "0$i";
            } else {
                $month = "$i";
            }

            $value = 0;
            $date_get = $fancy ? $this->month_format : 'm-Y';
            $label = date($date_get, strtotime("$year-$month-01"));

            if (time() < strtotime("$year-$month-01")) break;
            array_push($labels, $label);

            foreach ($this->data as $data) {
                if ($year == date('Y', strtotime($data->$date_column))) {
                    // Same year
                    if ($month == date('m', strtotime($data->$date_column))) {
                        // Same month
                        if ($this->preaggregated) {
                            $value = $data->aggregate;
                        } else {
                            $value-=$data->amount;
                        }
                    }
                }
            }

            array_push($values, $value);
        }

        $this->labels = $labels;
        $this->values = $values;

        return $this;
    }
}
