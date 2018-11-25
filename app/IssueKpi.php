<?php

namespace App;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class IssueKpi extends Model
{
    const NEW      = 1;
    const RESOLVED = 2;

    protected $guarded = [];
    protected $dates   = ['date'];

    public static function report($start, $end)
    {
        $period = CarbonPeriod::create($start, $end);
        $kpis   = static::whereBetween('date', [$period->first(), $period->last()])
                        ->groupBy('date')
                        ->groupBy('type')
                        ->select(DB::raw('sum(value) as value'), 'date', 'type')->get();

        $result = [];
        foreach ($period as $date) {
            $new = $kpis->first(function($kpi) use($date){
                return $kpi->type == IssueKpi::NEW && $kpi->date->toDateString() ==  $date->toDateString();
            })->value ?? 0;
            $fixed = $kpis->first(function($kpi) use($date){
                    return $kpi->type == IssueKpi::RESOLVED && $kpi->date->toDateString() ==  $date->toDateString();
            })->value ?? 0;

//            $new                           = $kpis->firstWhere(['date', $date->toDateString(), 'type' => IssueKpi::NEW])['value'] ?? 0;
//            $fixed                         = $kpis->firstWhere(['date', $date->toDateString(), 'type' => IssueKpi::FIXED])['value'] ?? 0;
            $result[$date->toDateString()] = [
                'new'       => $new,
                'fixed'     => $fixed,
            ];
        }
        return collect($result);
    }
}
