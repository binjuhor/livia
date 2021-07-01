<?php

namespace App\Invoices;

use App\Models\Project;

class InvoiceUtils
{
    public static function getWeeklyRef(Project $project): string
    {
        return sprintf(
            '%s-%s',
            $project->jira_key,
            now()->format('WY')
        );
    }

    public static function getJiraKey(string $description)
    {
        preg_match('/\((.*?)\)/', $description, $matches);

        if (2 === count($matches)) {
            return $matches[ 1 ];
        }

        return null;
    }
}