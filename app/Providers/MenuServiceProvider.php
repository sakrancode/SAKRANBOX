<?php

namespace App\Providers;

use App\Models\Document;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\View;


class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function getStorageFolder($co_id, $level, $parent_id=null)
    {
        if (!is_null($parent_id)) {
            $documents = Document::where([['co_id', $co_id], ['level', $level], ['type', 'FOLDER'], ['parent_id', $parent_id]])->get();
        } else {
            $documents = Document::where([['co_id', $co_id], ['level', $level], ['type', 'FOLDER']])->get();
        }

        $submenu = array();

        $count = 1;
        foreach ($documents as $document) {
            $subFolder = NULL;
            $submenu[$count]['text'] = $document->name;
            $submenu[$count]['url']  = 'document/'.$document->id.'/subFolder';
            $submenu[$count]['icon'] = 'fas fa-fw fa-folder';

            $subFolder = $this->getStorageFolder($co_id, ($level+1), $document->id);
            if (count($subFolder) > 0) {
                $submenu[$count]['submenu'] = $subFolder;
            }
            $count++;
        }

        return $submenu;
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        # Laravel session is initialized in a middleware so you can't access the session from a Service Provider,
        # because they execute before the middleware in the request lifecycle
        # Source : https://www.semicolonworld.com/question/50076/laravel-how-to-get-current-user-in-appserviceprovider

        # path disesuaikan dengan folder resources/views,
        # dalam hal ini seluruh page diletakkan kedalam folder pages,
        # maka semula *.* ditambah menjadi pages.*.*
        $path = "pages.*.*";

        view()->composer($path, function($view) use ($events){
        // View::composer(['*.*.*'], function ($view){
            // $view->with('coba', auth()->user());

            $user = Auth::user();
            // dd($user);
            $subMenu = $this->getStorageFolder($user->co_id, 1);
            // dd($subMenu);

           // if ($user->role=='S') {
               $events->listen(BuildingMenu::class, function (BuildingMenu $event) use ($subMenu) {
                   $event->menu->add([
                      'text'        => 'Dashboard',
                      'url'         => 'home',
                      'icon'        => 'fas fa-fw fa-tachometer-alt',
                      // 'label'       => 4,
                      // 'label_color' => 'success',
                   ]);
                   $event->menu->add([
                      'text'        => 'User',
                      'url'         => 'user',
                      'icon'        => 'fas fa-fw fa-users',
                      // 'label'       => 4,
                      // 'label_color' => 'success',
                   ]);
                   // $event->menu->add(['header' => 'account_settings']);
                   $event->menu->add('MAIN NAVIGATION');
                   $event->menu->add([
                       'text'        => 'All Files',
                       'url'         => 'publisher',
                       'icon'        => 'fas fa-fw fa-server',
                       'submenu' => $subMenu,
                   ]);
                   // $event->menu->add([
                   //     'text'    => 'Recent',
                   //     'url'  => 'purchase',
                   //     'icon'    => 'fas fa-fw fa-history',
                   // ]);
                   // $event->menu->add([
                   //     'text'    => 'Starred',
                   //     'url'  => 'purchase',
                   //     'icon'    => 'fas fa-fw fa-star',
                   // ]);
               });
           // }
        });
    }
}
