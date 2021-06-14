<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:create {user : The Name of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will receive a user name and create an API token that will be associated with the user';

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

        $user = User::query()->where('name', $this->argument('user'))->first();

        if ($user !== null) {
            $user->tokens()->delete();
        } else {
            $user = User::query()->create([
                'name' => $this->argument('user')
            ]);
        }

        $token = $user->createToken($this->argument('user'))->plainTextToken;
        $this->info($token);
    }
}
