<?php


namespace App\Issues;

use BenSampo\Enum\Enum;

final class IssueStatus extends Enum
{
    const ToDo = '0';
    const InProgress = '1';
    const Done = '2';
}