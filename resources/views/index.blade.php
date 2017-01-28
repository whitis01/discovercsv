@extends('layout')
@section('head')
    {!! Charts::assets() !!}
@stop
@section('content')
    <h1 class="highcharts-negative">-${{ $totals->getTotalDebit() }}</h1>
    {!! $chartDebit->render() !!}
    <h1 class="highcharts-color-0">${{ $totals->getTotalCredit() }}</h1>
    {!! $chartCredit->render() !!}

    <h1>Difference: ${{ $totals->getTotalCredit() - $totals->getTotalDebit() }}</h1>
@stop

