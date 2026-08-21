<?php
// app/Imports/ScholarshipsImport.php

namespace App\Imports;

use App\Models\Scholarship;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ScholarshipsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Scholarship([
            'title' => $row['title'],
            'slug' => Str::slug($row['title']),
            'description' => $row['description'] ?? null,
            'provider' => $row['provider'] ?? null,
            'university' => $row['university'] ?? null,
            'country' => $row['country'] ?? null,
            'amount' => $row['amount'] ?? null,
            'deadline' => !empty($row['deadline']) ? Carbon::parse($row['deadline']) : null,
            'apply_link' => $row['apply_link'] ?? null,
            'degree_level' => $row['degree_level'] ?? null,
            'scholarship_type' => $row['scholarship_type'] ?? null,
            'is_published' => ($row['status'] ?? 'draft') === 'published',
            'is_draft' => ($row['status'] ?? 'draft') === 'draft',
            'posted_by' => auth()->id() ?? 1,
        ]);
    }
}
