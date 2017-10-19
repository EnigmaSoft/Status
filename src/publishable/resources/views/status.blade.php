@extends('layouts.master')

@section('title', 'Status')

@section('page_title', 'Server Status')

@section('content')
    <h2 class="text-center" style="margin-bottom: 0px;">{{ config('server.world_name', 'Tespia') }} {!! $world_status ? '<span class="label label-success font-10" style="display: inline-block;vertical-align: middle;">ONLINE</span>' : '<span class="label label-danger font-10" style="display: inline-block;vertical-align: middle;">OFFLINE</span>' !!}</h2><hr style="margin-top: 0px;" />
    <div style="width:100%;display:block;overflow:hidden;">
    @foreach ($channel as $ch => $status)
        <div style="width:calc(100% / 3);float:left;background-color: #fcfcfc;padding:2px;{{ $loop->iteration > 3 ? 'border-top: 1px solid #f2f2f2;' : '' }}{{ !in_array($loop->iteration, [3, 6, 9, 12, 15, 18, 20]) ? 'border-right: 1px solid #f2f2f2;' : '' }}">
            <div class="pull-left" style="padding:2px;"><span class="pull-left" style="padding:1px 5px 0px 5px;display: inline-block;">Channel</span><code class="pull-left">#{{ $ch < 10 ? 0 : '' }}{{ $ch }}</code></div>
            <div class="pull-right" style="padding:2px 10px 2px 2px;">{!! $status ? '<span class="label label-success">ONLINE</span>' : '<span class="label label-danger">OFFLINE</span>' !!}</div>
        </div>
    @endforeach
    </div>
    @if ($count !== 'OFFLINE')
        <hr />
        <p class="text-center"><strong>Players Online:</strong> <span class="label label-success">{{ $count }}</span></p>
    @endif
@endsection
