@extends('layouts.admin')
@section('title','Detalle job')
@section('content')<h1 class="h4">Job #{{ $job->id }}</h1><div class="card"><div class="card-body"><pre>{{ json_encode($job->toArray(), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre></div></div>@endsection
