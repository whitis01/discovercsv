<?php

namespace App;

use App\Jobs\DatabaseChart;
use ConsoleTVs\Charts\Facades\Charts;
use ConsoleTVs\Charts\Builder\Database;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = ['trans_date', 'post_date', 'description', 'amount', 'category'];

    private $totalDebit  = 0;
    private $totalCredit = 0;

    /**
     * @param string $type default 'bar'
     * @param boolean $debit default true
     * @return Database|null
     */
    public function makeChart($type = 'bar', $debit = true)
    {
        $chart = null;
        $date = 'post_date';
        $data = Record::all('amount', $date);

        $year = trim(strrchr($data->first()->$date, '/'), '/');
        $yearLast = trim(strrchr($data->last()->$date, '/'), '/');

        $data = $debit ? $data->filter(function($items) { return $items->amount > 0; }) :
                         $data->filter(function($items) { return $items->amount <= 0; });
        while ($year <= $yearLast) {
            $chart1 = new DatabaseChart($data, $type, 'highcharts');
            $chart1->dimensions(500, 1000)
                ->dateColumn($date)
                ->groupByMonth($year, true)
                ->responsive(true);
            // TODO: Bleh, this needs to look nicer.
            if ($chart !== null) {
                $chart->values = array_merge($chart->values, $chart1->values);
                $chart->labels = array_merge($chart->labels, $chart1->labels);
            } else {
                $chart1->title($debit ? 'Discover Card Debits' : 'Discover Card Credits');
                $chart = $chart1;
            }
            $year++;
        }

        return $chart;
    }

    public function setTotalTransactions()
    {
        $data = Record::all('amount');

        $this->totalDebit = $data->filter(function($items) { return $items->amount > 0; })->sum('amount');
        $this->totalCredit = -$data->filter(function($items) { return $items->amount <= 0; })->sum('amount');
    }

    public function getTotalDebit()
    {
        return $this->totalDebit;
    }

    public function getTotalCredit()
    {
        return $this->totalCredit;
    }
}
