<?php

namespace App\Console\Commands;

use App\Models\LeadAssignmentSetting;
use App\Services\SmartAssignService;
use Illuminate\Console\Command;

class CalculateSalesPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales:calculate-performance
                            {--period=monthly : Period type (daily, weekly, monthly)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and cache sales performance metrics for all sales persons';

    /**
     * Execute the console command.
     */
    public function handle(SmartAssignService $service): int
    {
        $period = $this->option('period') ?? LeadAssignmentSetting::getCalculationPeriod();

        $this->info("Calculating {$period} performance for all sales users...");

        $service->calculateAllPerformance($period);

        $this->info('Performance calculation completed successfully!');

        return Command::SUCCESS;
    }
}
