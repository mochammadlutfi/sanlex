<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use App\Helpers\MenuHelper;
class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $data = [
            ...parent::share($request),
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'menu' => MenuHelper::get(),
            'settings' => [
                'logo_light' => '/images/logo/logo_light.png',
                'logo_light_sm' => '/images/logo/logo_sm.png',
                'logo_dark' => '/images/logo/logo_light.png',
                'logo_dark_sm' => '/images/logo/logo_sm.png',
            ]
        ];

        if($user){
            $data['auth']['user'] = [
                'name' => $user->name,
                'email' => $user->email,
                'image' => $user->image
            ];
        }


        // return [
        //     'auth' => [
        //         'user' => [
        //             'name' => $user->partner->name,
        //             'role' => $user->roles[0]->name,
        //             'email' => $user->partner->email,
        //             'image' => $user->partner->image
        //         ],
        //     ],
        // ];

        
        return array_merge(parent::share($request), $data);
    }
}
