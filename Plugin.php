<?php namespace Genius\Base;

use Backend\Facades\Backend;
use System\Classes\PluginBase;

/**
 * Base Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = [
        // 'RainLab.Translate',
        // 'Flynsarmy.IdeHelper',
        // 'BnB.ScaffoldTranslation',
        'October.Drivers',
        'RainLab.GoogleAnalytics',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'genius.base::lang.plugin.name',
            'description' => 'genius.base::lang.plugin.description',
            'author'      => 'Genius',
            'icon'        => 'icon-puzzle-piece'
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'lang' => function() { return \RainLab\Translate\Classes\Translator::instance()->getLocale(); },
            ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'genius.base::contact' => 'Contato efetuado através do site.',
            'genius.base::register' => 'Novo cadastro através do site.',
            'genius.base::restore' => 'Recuperação de senha administrativa.',
            'genius.base::invite' => 'Convite para area administrativa.'
        ];
    }

    public function registerNavigation()
    {
        return [
            'models' => [
                'label'       => 'genius.base::lang.menu.models',
                'url'         => Backend::url('genius/base/index/models'),
                'icon'        => 'icon-sitemap',
                'order'       => 17,
            ],
            'pages' => [
                'label'       => 'genius.base::lang.menu.pages',
                'url'         => Backend::url('genius/base/index/pages'),
                'icon'        => 'icon-pencil-square-o',
                'order'       => 18,
            ],
            'admin' => [
                'label'       => 'genius.base::lang.menu.admin',
                'url'         => Backend::url('genius/base/index/admin'),
                'icon'        => 'icon-sliders',
                'order'       => 19
            ]
        ];
    }

    public function boot()
    {
        \Carbon\Carbon::setLocale('pt_BR');


//        Event::listen('backend.menu.extendItems', function($manager) {
//            $manager->addSideMenuItems('Genius.Base', 'models', [
//                'customers' => [
//                    'label' => 'genius.customers::lang.customers.menu_label',
//                    'icon' => 'icon-coffee',
//                    'url' => Backend::url('genius/customers/customers'),
//                    'order' => 20,
//                ],
//            ]);
//        });
    }

}
