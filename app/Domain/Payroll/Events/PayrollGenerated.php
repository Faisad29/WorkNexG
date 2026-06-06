<?php

namespace App\Domain\Payroll\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PayrollGenerated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly string $payrollId,
        public readonly string $companyId,
    ) {
    }
}
