<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LegalCase extends Model
{
    protected $table = 'cases';
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'receiving_date' => 'date',
            'filing_date' => 'date',
            'hearing_date' => 'date',
            'next_hearing_date' => 'date',
            'judgement_date' => 'date',
            'limitation_date' => 'date',
            'case_charge' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function oppositeClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'opposite_client_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CaseCategory::class, 'case_category_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    public function leadLawyer(): BelongsTo
    {
        return $this->belongsTo(Lawyer::class, 'lead_lawyer_id');
    }

    public function lawyers(): BelongsToMany
    {
        return $this->belongsToMany(Lawyer::class, 'case_lawyer', 'case_id', 'lawyer_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function acts(): BelongsToMany
    {
        return $this->belongsToMany(Act::class, 'case_acts', 'case_id', 'act_id')
            ->withPivot('sections', 'notes')
            ->withTimestamps();
    }

    public function hearingDates(): HasMany
    {
        return $this->hasMany(HearingDate::class, 'case_id')->orderBy('date', 'desc');
    }

    public function judgments(): HasMany
    {
        return $this->hasMany(Judgment::class, 'case_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CaseNote::class, 'case_id')->latest();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CaseDocument::class, 'case_id')->latest();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'case_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'case_id');
    }

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class, 'case_id');
    }

    public function connectedCases(): BelongsToMany
    {
        return $this->belongsToMany(LegalCase::class, 'connected_matters', 'parent_case_id', 'connected_case_id')
            ->withPivot('relationship_type', 'notes')
            ->withTimestamps();
    }

    // Scopes for Daily Board & Cause Lists
    public function scopeTodayHearings(Builder $query): Builder
    {
        return $query->whereDate('next_hearing_date', Carbon::today());
    }

    public function scopeTomorrowHearings(Builder $query): Builder
    {
        return $query->whereDate('next_hearing_date', Carbon::tomorrow());
    }

    public function scopeDateAwaited(Builder $query): Builder
    {
        return $query->whereNull('next_hearing_date')->where('status', '!=', 'Closed');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', ['Closed', 'Judgement']);
    }
}
