<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class Generate_IV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'IV:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a valid IV calculated using app.cipher and set it in .env file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $iv = base64_encode(random_bytes(openssl_cipher_iv_length($this->laravel['config']['app.cipher'])));
        echo "\n";
        echo "\n";
        echo "Copy the following line and replace in .env (line 4)]\n";
        echo "APP_FIX_IV_FOR_EMAIL=$iv\n\n";
        return Command::SUCCESS;
    }
}
