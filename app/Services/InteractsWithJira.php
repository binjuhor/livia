<?php


namespace App\Services;

use JiraRestApi\Project\ProjectService;

trait InteractsWithJira
{
    public function getProject(string $key)
    {
        return (new ProjectService)->get($key);
    }
}