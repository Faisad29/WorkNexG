<?php

namespace App\Domain\Compliance\Models;

use App\Domain\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;
    use HasUuids;
    use BelongsToCompany;

    protected $fillable = [
        'org_id',
        'employee_id',
        'type',
        'document_number',
        'file_url',
        'issue_date',
        'expiry_date',
        'metadata',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'metadata' => 'array',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(\App\Domain\Employee\Models\Employee::class);
    }
}
