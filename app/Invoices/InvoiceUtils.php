<?php

namespace App\Invoices;

use App\Models\Project;

class InvoiceUtils
{
    public static function generateWeeklyInvoiceReference(Project $project): string
    {
        return sprintf(
            '%s-%s',
            $project->jira_key,
            now()->format('WY')
        );
    }
}