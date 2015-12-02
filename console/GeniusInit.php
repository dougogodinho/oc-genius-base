<?php namespace Genius\Base\Console;

use Backend\Models\BrandSettings;
use Cms\Classes\Theme;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Artisan;
use Backend\Models\User;
use System\Models\File;
use System\Models\MailSettings;
use RainLab\GoogleAnalytics\Models\Settings as AnalytcsSettings;

class GeniusInit extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'genius:init';

    /**
     * @var string The console command description.
     */
    protected $description = 'Install the basis for Genius projects.';

    /**
     * Execute the console command.
     * @return void
     */
    public function fire()
    {

        $base_path = base_path('');

        $this->info('Dependencies installing begins here! (plugin:install)');

        // DEPENDENCIES
        foreach ([
                     'RainLab.Translate',
                     'Flynsarmy.IdeHelper',
                     'BnB.ScaffoldTranslation',
                     'October.Drivers',
                     'RainLab.GoogleAnalytics',
                     'Genius.StorageClear',
                 ] as $required) {

            $this->info('Installing: ' . $required);
            Artisan::call("plugin:install",['name'=>$required]);
        }
        $this->info('Dependencies installed!');

        // THEME
        $this->info('Installing: oc-genius-theme');
        system("cd '$base_path' && git clone https://github.com/estudiogenius/oc-genius-theme themes/genius");
        Theme::setActiveTheme('genius');

        // ELIXIR
        $this->info('Installing: oc-genius-elixir');
        system("cd '$base_path' && git clone https://github.com/estudiogenius/oc-genius-elixir plugins/genius/elixir");

        // FORMS
        $this->info('Installing: oc-genius-forms');
        system("cd '$base_path' && git clone https://github.com/estudiogenius/oc-genius-forms plugins/genius/forms");

        // BACKUP
        $this->info('Installing: oc-genius-backup');
        system("cd '$base_path' && git clone https://github.com/estudiogenius/oc-genius-backup plugins/genius/backup");


        // GOOGLE ANALYTICS
        $this->info('Initial setup: AnalytcsSettings');
        if (AnalytcsSettings::get('project_name')) {
            AnalytcsSettings::create([
                'project_name' => 'API Project',
                'client_id' => '979078159189-8afk8nn2las4vk1krbv8t946qfk540up.apps.googleusercontent.com',
                'app_email' => '979078159189-8afk8nn2las4vk1krbv8t946qfk540up@developer.gserviceaccount.com',
                'profile_id' => '112409305',
                'tracking_id' => 'UA-29856398-24',
                'domain_name' => 'retrans.srv.br',
            ])->gapi_key()->add(File::create([
                'data' => plugins_path('genius/base/assets/genius-analytics.p12'),
            ]));
        }

        // EMAIL
        $this->info('Initial setup: MailSettings');
        if (MailSettings::get('sender_name')) {
            MailSettings::create([
                'send_mode' => 'mandrill',
                'sender_name' => 'Genius Soluções Web',
                'sender_email' => 'contato@estudiogenius.com.br',
                'mandrill_secret' => 't27R2C15NPnZ8tzBrIIFTA',
            ]);
        }

        // BRAND
        $this->info('Initial setup: BrandSettings');
        if (BrandSettings::get('app_name')) {
            BrandSettings::create([
                'app_name' => 'Genius Soluções Web',
                'app_tagline' => 'powered by Genius',
            ])->logo()->add(File::create([
                'data' => plugins_path('genius/base/assets/genius-logo.png'),
            ]));
        }

        // USUARIO BASE
        $this->info('Initial setup: User');
        $user = User::find(1);
        if (!$user->last_name) {
            $user->update([
                'first_name' => 'Genius',
                'last_name' => 'Soluções Web',
                'login' => 'genius',
                'email' => 'contato@estudiogenius.com.br',
                'password' => 'genius',
                'password_confirmation' => 'genius',
            ]);
            $user->avatar()->add(File::create([
                'data' => plugins_path('genius/base/assets/genius-avatar.jpg'),
            ]));
        }

        $this->info('Genius.Base is ready to rock!');
        $this->info('');
        $this->info('For Laravel Elixir setup run: php artisan elixir:init');
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
