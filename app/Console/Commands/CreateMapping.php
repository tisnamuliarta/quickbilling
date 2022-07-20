<?php

namespace App\Console\Commands;

use App\Models\Financial\AccountMapping;
use IFRS\Models\ReportingPeriod;
use Illuminate\Console\Command;

class CreateMapping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mapping {types} {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Account Mapping';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $option_name = $this->argument('key');
        $option_type = $this->argument('types');

        $check_option = AccountMapping::where('name', $option_name)
            ->where('type', $option_type)
            ->first();
        if (! $check_option) {
            $reporting_period = ReportingPeriod::where('calendar_year', date('Y'))->first();
            AccountMapping::create([
                'name' => $option_name,
                'type' => $option_type,
                'account_id' => 0,
                'reporting_period_id' => $reporting_period->id,
            ]);
            $this->info('Account mapping created successfully!');
        } else {
            $this->error('Account mapping name already exist!');
        }

        return 0;
    }
}
