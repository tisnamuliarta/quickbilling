<?php

namespace App\Console\Commands;

use App\Models\Option;
use Illuminate\Console\Command;

class CreateOption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:option {name} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and setting options';

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
        $option_name = $this->argument('name');
        $option_type = $this->argument('type');

        $check_option = Option::where('option_name', '=', $option_name)->first();
        if (!$check_option) {
            Option::create([
                'option_name' => $option_name,
                'option_type' => $option_type
            ]);
            $this->info('Option created successfully!');
        } else {
            $this->info('Option name already exist!');
        }
    }
}
