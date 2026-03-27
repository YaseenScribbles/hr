<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            // keep the default shared props (flash, errors, etc.)
            ...parent::share($request),

            // replicate the types we declared in TypeScript.  The
            // auth/user object can be anything you like; make sure it
            // matches what the front‑end expects.
            'auth' => [
                'user' => $request->user()
                    ? ['id' => $request->user()->id, 'name' => $request->user()->name, 'role' => $request->user()->role]
                    : null,
            ],

            // enforce a consistent flash structure so TS can pick it up.
            'flash' => [
                'toast' => $request->session()->get('toast'),
            ],

            //companies assigned to the user
            'user_companies' => $request->user() ? $request->user()->companies : [],
        ];
    }
}
