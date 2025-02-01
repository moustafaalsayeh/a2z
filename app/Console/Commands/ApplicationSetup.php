<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApplicationSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup your application';

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
        $this->warn("Setting up your application ...");
        $this->call('config:cache');
        $this->call('storage:link');
        Schema::disableForeignKeyConstraints();
        // Clear DB
        $this->call('migrate:fresh');
        $this->line("✔");
        // Install Passport
        $this->warn("Installing Passport ...");
        $this->call('passport:install', ['--force']);
        $this->line("✔");
        // Change Passport Client Secret
        $this->warn("Updaing Client Secret ...");
        DB::table('oauth_clients')->where('id', 2)->update(['secret' => 'KNGMHksrLvq4R962gk7qCXmOY960GLQRFEzrRXRv']);
        $this->line('-------------');
        $this->line('Client Secret: ');
        $this->line('KNGMHksrLvq4R962gk7qCXmOY960GLQRFEzrRXRv');
        // Database seeding
        $this->warn("Dummy Data is being generated ...");
        $this->call('module:seed', ['module' => 'Country', '--class' => 'CountriesTableSeeder']);
        $this->line('Countreis Seeded');
        $this->call('module:seed', ['module' => 'Country', '--class' => 'LanguagesTableSeeder']);
        $this->line('Languages Seeded');
        $this->call('module:seed', ['module' => 'Currency']);
        $this->line('Currencies Seeded');
        $this->call('module:seed', ['module' => 'APIAuth']);
        $this->line('Users Seeded');
        $this->call('module:seed', ['module' => 'Permissions', '--class' => 'RolesTableSeeder']);
        $this->line('Roles Seeded');
        $this->call('module:seed', ['module' => 'Permissions', '--class' => 'PermissionsTableSeeder']);
        $this->line('Permissions Seeded');
        $this->call('module:seed', ['module' => 'Outlet']);
        $this->line('Outlets Seeded');
        $this->call('module:seed', ['module' => 'Product']);
        $this->line('Products Seeded');
        $this->call('module:seed', ['module' => 'ProductSpecification']);
        $this->line('Menus Seeded');
        $this->call('module:seed', ['module' => 'Menu']);
        $this->line('Product Specifications Seeded');
        $this->call('module:seed', ['module' => 'Review']);
        $this->line('Reviews Seeded');
        $this->call('module:seed', ['module' => 'GlobalSetting']);
        $this->line('Global Settings Seeded');

        // Currencies seeding
        // $this->warn("Adding Available Currencies ...");
        // $this->call('currency:manage add usd,gbp,cad,aud,sek,inr,cny,eur,egp');
        // $this->call('currency:update -o');
        // Linking storage
        $this->warn("Storage is being linked ...");
        $this->call('storage:link');
        // All done successfully
        $this->comment("All Done ✔  Keep Calm And Start Coding ...");
        // Queue listen
        // $this->warn("Listening to queue processes ...");
        // $this->call('queue:listen');
    }
}
