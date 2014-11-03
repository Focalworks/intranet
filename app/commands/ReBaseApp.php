<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Foundation\Artisan;

class ReBaseApp extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:rebase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the CMS to starting point.';

    /**
     * Create a new command instance.
     *
     * @return \ReBaseApp
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // reseting the migrations
        $this->info('Reset the migrations');
        $this->call('migrate:reset');

        // running the sentry migrations
        $this->info('Running Sentry migrations');
        $this->call('migrate', array('--package' => 'cartalyst/sentry'));
         
        // running scripts for L4Mod SentryUser
        $this->info('Running Sentry migrations');
        $this->call('migrate', array('--bench' => 'l4mod/sentryuser'));
        
        // workbench migrations for focalworks modules
        $this->call('migrate', array('--bench' => 'focalworks/grievance'));
        $this->call('migrate', array('--bench' => 'focalworks/filemanaged'));
        $this->call('migrate', array('--bench' => 'focalworks/comment'));
        $this->call('migrate', array('--bench' => 'focalworks/kanbanize'));
        $this->call('migrate', array('--bench' => 'focalworks/quiz'));
        $this->call('migrate', array('--bench' => 'focalworks/assessment'));
        $this->call('migrate', array('--bench' => 'focalworks/mailing'));

        $this->call('asset:publish', array('debugbar/laravel-debugbar'));
        $this->call('asset:publish', array('--bench' => 'l4mod/sentryuser'));
        $this->call('asset:publish', array('--bench' => 'focalworks/comment'));
        $this->call('asset:publish', array('--bench' => 'focalworks/mailing'));

        // calling default migrations
        $this->call('migrate');

        $this->call('db:seed');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
// 			array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
// 			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}