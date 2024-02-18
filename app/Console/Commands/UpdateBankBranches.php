<?php

namespace App\Console\Commands;

use App\Models\BankBranch;
use Illuminate\Console\Command;

class UpdateBankBranches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'banks-branches:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the bank_branches table and fill it with data again.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Truncating bank_branches table...');
        BankBranch::truncate();
        $this->info('Bank branches table truncated.');

        // Fill the bank_branches table with data again
        $this->call('db:seed', ['--class' => 'BanksBranchesSeeder']);

        $this->info('Bank branches table filled with data again.');
    }
}
