<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Print_IV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calambre:show_IV';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a valid IV calculated using app.cipher';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo base64_encode(random_bytes(openssl_cipher_iv_length($this->laravel['config']['app.cipher'])));
        echo "\n";
        return Command::SUCCESS;
    }
}
