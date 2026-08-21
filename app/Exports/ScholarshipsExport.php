<?php
// app/Exports/ScholarshipsExport.php

namespace App\Exports;

use App\Models\Scholarship;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ScholarshipsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function query()
    {
        return Scholarship::query()->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Provider',
            'University',
            'Country',
            'Amount',
            'Deadline',
            'Degree Level',
            'Type',
            'Status',
            'Source',
            'Created At'
        ];
    }

    public function map($scholarship): array
    {
        return [
            $scholarship->id,
            $scholarship->title,
            $scholarship->provider ?? 'N/A',
            $scholarship->university ?? 'N/A',
            $scholarship->country ?? 'N/A',
            $scholarship->amount ?? 'N/A',
            $scholarship->deadline ?? 'N/A',
            $scholarship->degree_level ?? 'N/A',
            $scholarship->scholarship_type ?? 'N/A',
            $scholarship->is_published ? 'Published' : 'Draft',
            $scholarship->source ?? 'manual',
            $scholarship->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
