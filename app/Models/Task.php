<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    // Specifying fillable fields to prevent mass assignment vulnerability
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
        'status',
        'assigned_to'
    ];

    // Cast attributes to specific types.
    protected $casts = [
        'due_date' => 'datetime', 
    ];

    // Attributes that should be treated as dates.
    protected $dates = ['due_date', 'deleted_at'];

    // The primary key associated with the table.
    protected $primaryKey = 'task_id';
	
	// Indicates if the IDs are auto-incrementing.
	public $incrementing = true;

	// The data type of the primary key ID.
    protected $keyType = 'int'; 

    // The table associated with the model.
    protected $table = 'my_tasks';
    
    // Define the new names for the timestamp fields
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    /**
     * Get the user that owns the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor to format the `due_date` attribute when retrieving it.
     *
     * @param string $value
     * @return string
     */
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    /**
     * Mutator to format the `due_date` attribute before storing it.
     *
     * @param string $value
     * @return void
     */
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat('d-m-Y H:i', $value);
    }

    /**
     * Scope to filter tasks by priority.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope to filter tasks by status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
