<?php

namespace App\Http\Middleware;

use Closure;
use App\Rules\LocaleRule;

class Translatable
{
    protected $languageCodes;
    private $availableForTranslationSaving;

    public function __construct()
    {
        $this->languageCodes = [
            'ar' => config('translatable.locale_codes.ar'),
            'en' => config('translatable.locale_codes.en'),
        ];

        $this->availableForTranslationSaving =
            in_array(
                request()->method(),
                [
                    'PUT',
                ]
            )
            &&
            empty(array_intersect(
                    config('translatable.notTranslatableSegments'),
                    request()->segments()
                ));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->availableForTranslationSaving) {
            $request->validate([
                'locale' => ['required', new LocaleRule]
            ]);
        }

        if ($request->hasHeader('X-locale') && in_array($request->header('X-locale'), config('translatable.locales'))) {
            app()->setLocale($request->header('X-locale'));

            setLocale(LC_TIME, $this->languageCodes[$request->header('X-locale')]);
            return $next($request);
        }

        app()->setLocale(config('translatable.fallback_locale'));
        return $next($request);
    }
}
