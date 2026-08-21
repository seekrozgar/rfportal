<?php
// app/Exports/ScholarshipsExport.php

namespace App\Exports;

use App\Models\Scholarship;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ScholarshipsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * ✅ Query for export
     * 🛠️ FIXED: Added strict union types required by Maatwebsite interface in PHP 8.3
     */
    public function query(): EloquentBuilder|QueryBuilder|Relation
    {
        return Scholarship::query()
            ->select([
                'id',
                'title',
                'description',
                'provider',
                'university',
                'country',
                'amount',
                'deadline',
                'degree_level',
                'scholarship_type',
                'apply_link',
                'source',
                'is_published',
                'is_draft',
                'created_at'
            ])
            ->orderBy('created_at', 'desc');
    }

    /**
     * ✅ Headings for the export
     */
    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Provider',
            'University',
            'Country',
            'Amount',
            'Deadline',
            'Degree Level',
            'Scholarship Type',
            'Apply Link',
            'Source',
            'Status',
            'Is Draft',
            'Created At',
        ];
    }

    /**
     * ✅ Map each row of data
     */
    public function map($scholarship): array
    {
        return [
            $scholarship->id,
            $scholarship->title,
            strip_tags($scholarship->description ?? ''),
            $scholarship->provider ?? 'N/A',
            $scholarship->university ?? 'N/A',
            $scholarship->country ?? 'N/A',
            $scholarship->amount ?? 'N/A',
            $scholarship->deadline ? \Carbon\Carbon::parse($scholarship->deadline)->format('Y-m-d') : 'N/A',
            $scholarship->degree_level ?? 'N/A',
            $scholarship->scholarship_type ?? 'N/A',
            $scholarship->apply_link ?? 'N/A',
            $scholarship->source ?? 'Manual',
            $scholarship->is_published ? 'Published' : 'Draft',
            $scholarship->is_draft ? 'Yes' : 'No',
            $scholarship->created_at ? $scholarship->created_at->format('Y-m-d H:i:s') : 'N/A',
        ];
    }

    /**
     * ✅ Apply styles to the sheet
     */
    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A' => ['width' => 10],
            'B' => ['width' => 40],
            'C' => ['width' => 50],
            'D' => ['width' => 20],
            'E' => ['width' => 25],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 15],
            'I' => ['width' => 15],
            'J' => ['width' => 20],
            'K' => ['width' => 30],
            'L' => ['width' => 15],
            'M' => ['width' => 15],
            'N' => ['width' => 10],
            'O' => ['width' => 20],
        ];
    }
}
