<?php

namespace App\Domain\Approval\Interfaces;

interface ApprovalServiceInterface
{
    public function createApproval(array $data): void;
    public function updateApproval(array $data): void;
}
