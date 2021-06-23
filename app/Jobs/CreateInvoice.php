<?php

namespace App\Jobs;

use App\Invoices\InvoiceStatus;
use App\Models\Project;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateInvoice implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithProjectModel;

    protected Project $project;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $project   = $this->project;
        $reference = sprintf(
            '%s-%s',
            $project->jira_key,
            now()->format('WY')
        );
        //@TODO Put 20 to configuration
        $total = $this->getProjectDoneIssues($project)
                      ->sum('story_point') * 20;

        $project->invoices()->create([
            'reference' => $reference,
            'total'     => $total,
            'status'    => InvoiceStatus::Draft,
            'xero_id'   => null
        ]);
    }
}
