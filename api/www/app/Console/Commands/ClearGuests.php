<?php

namespace App\Console\Commands;

use App\User;
use DateTime;
use Illuminate\Console\Command;
use Throwable;

class ClearGuests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'warpg:clear-guests {--all} {--days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes guest users from the database
        {--all : Removes all guests}
        {--days : Removes guests updated after this number of days (default: 30)}';

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
     * @return mixed
     */
    public function handle()
    {
        // base query for guests
        $query = User::where('guest', true);

        // checks if all guests will be deleted and asks for confirmation
        if ($this->option('all')) {
            if (!$this->confirm('Remove all guests?')) {
                return;
            }
        } else {
            // minimum number of days the guests should have been updated
            $days = $this->option('days');
            $query->where('updated_at', '<', new DateTime("-{$days} days"));
        }

        try {
            $count = $query->delete();
            $this->info("{$count} guests removed.");
        } catch (Throwable $t) {
            $this->error('Error removing guests: ' . $t->getMessage());
        }
    }
}
