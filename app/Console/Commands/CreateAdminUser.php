<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "CreateAdminUser";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create admin level user to access admin side APIs";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $adminData = [];

        $adminData["name"] = $this->ask("Please give admin user name like - Jon Deo");

        $adminData["email"] = $this->ask("Please give admin user email");

        $adminData["password"] = $this->secret("Please enter admin user password");

        $adminData["password_confirmation"] = $this->secret("Please enter admin user password again for confirmation");

        $validator = Validator::make(
            $adminData,
            [
                "name" => "required|string|max:100",
                "email" => "required|string|email|max:100",
                "password" => ["required", "string", "confirmed", Password::default()]
            ],
            [],
            [
                "name" => "Admin user name",
                "email" => "Email",
                "password" => "Password"
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }
            return Command::INVALID;
        }
        $adminData["password"] = Hash::make($adminData["password"]);
        unset($adminData["password_confirmation"]);
        $admin = Admin::create($adminData);
        $this->info("Admin created successfully Please copy below API token to interact with App");
        $this->comment($admin->createToken("LoginToken")->plainTextToken);
        return Command::SUCCESS;
    }
}
