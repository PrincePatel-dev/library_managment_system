<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BookIssue extends Model
{
    use HasFactory;

    public const ISSUE_DURATION_DAYS = 15;
    public const DAILY_PENALTY_RUPEES = 20;

    // Fields that can be mass assigned
    protected $fillable = [
        'book_id',
        'user_id',
        'issue_date',
        'due_date',
        'return_date',
        'status',
    ];

    // Cast dates properly
    protected $casts = [
        'issue_date'  => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

    // Belongs to a book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Belongs to a user (student)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if this issue is overdue
    public function isOverdue()
    {
        return in_array($this->status, ['issued', 'overdue'])
            && $this->due_date
            && Carbon::today()->greaterThan($this->due_date);
    }

    // How many days overdue
    public function daysOverdue()
    {
        if (!$this->due_date) {
            return 0;
        }

        $endDate = $this->status === 'returned' && $this->return_date
            ? $this->return_date
            : Carbon::today();

        if ($endDate->greaterThan(Carbon::today())) {
            $endDate = Carbon::today();
        }

        if ($endDate->lessThanOrEqualTo($this->due_date)) {
            return 0;
        }

        return $this->due_date->diffInDays($endDate);
    }

    // Penalty amount in rupees
    public function penaltyAmount()
    {
        return $this->daysOverdue() * self::DAILY_PENALTY_RUPEES;
    }
}
