<?php


namespace App\Issues;

use BenSampo\Enum\Enum;

final class IssueType extends Enum
{
    const Story = '0';
    const Task = '1';
    const Bug = '2';
    const Subtask = '3';
}