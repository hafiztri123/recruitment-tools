<?php

namespace App\Domain\Interview\Interfaces;


interface InterviewServiceInterface
{
    public function createInterview(array $data): void;
}
