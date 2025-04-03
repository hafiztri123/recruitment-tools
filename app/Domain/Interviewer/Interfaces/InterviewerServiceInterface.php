<?php


namespace App\Domain\Interviewer\Interfaces;

interface InterviewerServiceInterface
{
    public function assignInterviewer(array $data): void;
    public function assignInterviewers(array $multipleData): string;
}


