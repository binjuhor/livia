<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Models;

use App\Projects\IssueType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string summary
 * @property string jira_key
 * @property float  story_point
 */
class Issue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'summary',
        'jira_key',
        'project_id',
        'story_point',
        'type',
        'status'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
