<?php namespace Genius\Base\Updates;

use Artisan;
use Backend\Models\BrandSettings;
use Backend\Models\User;
use Cms\Classes\Theme;
use October\Rain\Database\Updates\Migration;
use System\Models\File;
use System\Models\MailSettings;
use RainLab\GoogleAnalytics\Models\Settings as AnalytcsSettings;

class StartPlugin extends Migration
{

    public function up()
    {
        $base_path = base_path('');

        // DEPENDENCIES
        foreach ([
                     'RainLab.Translate',
                     'Flynsarmy.IdeHelper',
                     'BnB.ScaffoldTranslation',
                     'October.Drivers',
                     'RainLab.GoogleAnalytics',
                     'Genius.StorageClear',
                 ] as $required) {

            Artisan::call("plugin:install",['name'=>$required]);
        }

        // THEME
        system("cd $base_path && git clone https://github.com/estudiogenius/oc-genius-theme themes/genius");
        Theme::setActiveTheme('genius');

        // ELIXIR
        system("cd $base_path && git clone https://github.com/estudiogenius/oc-genius-elixir plugins/genius/elixir");

        // FORMS
        system("cd $base_path && git clone https://github.com/estudiogenius/oc-genius-forms plugins/genius/forms");

        // BACKUP
        system("cd $base_path && git clone https://github.com/estudiogenius/oc-genius-backup plugins/genius/backup");


        // GOOGLE ANALYTICS
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

        // EMAIL
        MailSettings::create([
            'send_mode' => 'mandrill',
            'sender_name' => 'Genius Soluções Web',
            'sender_email' => 'contato@estudiogenius.com.br',
            'mandrill_secret' => 't27R2C15NPnZ8tzBrIIFTA',
        ]);

        // BRAND
        BrandSettings::create([
            'app_name' => 'Genius Soluções Web',
            'app_tagline' => 'powered by Genius',
        ])->logo()->add(File::create([
            'data' => plugins_path('genius/base/assets/genius-logo.png'),
        ]));

        // USUARIO BASE
        $user = User::find(1);
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

        // FINALIZA E INSTALA DEPENDÊNCIAS
        Artisan::call('october:up');
        Artisan::call('elixir:init');
    }

    public function down()
    {

    }

}
