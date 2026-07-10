@extends('layouts.admin')

@section('title', $title)
@section('page-title', $title)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $title }}</h1>
            <p class="text-secondary mb-0">Informacion calculada desde registros reales del sistema.</p>
        </div>
        <x-admin.report-export-buttons :report-type="$reportType" :filters="$filters" />
    </div>

    <x-admin.report-filters :filters="$filters" />

    <div class="row g-3 mb-4">
        @foreach (($report['summary'] ?? []) as $label => $value)
            <x-admin.report-card :label="$label" :value="$value" />
        @endforeach
    </div>

    <x-admin.report-table :columns="$report['columns'] ?? []" :rows="$report['rows'] ?? []" :paginator="$report['paginator'] ?? null" />
@endsection
