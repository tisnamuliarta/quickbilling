<?php

namespace App\Console\Commands;

use App\Models\Settings\Setting;
use Illuminate\Console\Command;

class CreateOption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:setting {types} {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an setting options';

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

        $check_option = Setting::where('key', '=', $option_name)->first();
        if (! $check_option) {
            Setting::create([
                'key' => $option_name,
                'types' => $option_type,
                'entity_id' => 0,
            ]);
            $this->info('Setting created successfully!');
        } else {
            $this->info('Setting name already exist!');
        }
    }
}
