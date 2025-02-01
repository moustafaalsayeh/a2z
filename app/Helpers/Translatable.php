<?php

namespace App\Helpers;

use ReflectionClass;

trait Translatable
{
    private $translationData;
    private $locale;

    public static function bootTranslatable()
    {
        if (!request()->isMethod('get')) {
            static::saved(function ($model) {
                if (!request()->dontTranslate) {
                    return $model->translate(request()->all());
                }
            });
        }
    }

    public function __get($property)
    {
        return in_array($property, $this->translatedAttributes) ? $this->findTranslatedAttr($property) : Parent::__get($property);
    }

    // ? Define translation model relation
    public function translations()
    {
        $className = new ReflectionClass($this);
        // dd($className->getName());
        $translationModelClass = $className->getName() . 'Translation';
        return $this->hasMany($translationModelClass);
    }

    // save model translation
    public function translate($data, $locale = null)
    {
        $this->locale = $locale ?? request()->locale;
        $this->modifyOrMakeTranslation($data);
        return $this;
    }

    public function hasFallbackTranslation()
    {
        return (bool) optional(optional($this->translations)->where('locale', config('translatable.fallback_locale')))->first();
    }

    private function findTranslatedAttr($attribute)
    {
        $xLocale = $this->getAvailableLocale();
        $translation = $this->translations->where('locale', $xLocale)->first();
        //dd($this->getAvailableLocale());
        return $translation ? $translation[$attribute] : $this->translations->where('locale', config('translatable.fallback_locale'))->first()[$attribute];
    }

    private function getAvailableLocale($source = null)
    {
        if ($source === 'locale') {
            return $this->getValidLocale();
        }
        return $this->getValidXlocale();
    }

    private function modifyOrMakeTranslation($data)
    {
        $locale = in_array($this->locale, config('translatable.locales')) ? $this->locale : config('translatable.fallback_locale');
        $translationData = array_merge($this->getTranslatableFields($data), ['locale' => $locale]);

        if (count($translationData) > 1) {
            $this->translations()->updateOrCreate(
                ['locale' => $locale],
                $translationData
            );
            return $this->refresh();
        }

        return $this;
    }

    private function getTranslatableFields($data)
    {
        $filteredData = collect($data)->filter(function ($field, $key) {
            return in_array($key, $this->translatedAttributes);
        })->toArray();

        return $filteredData;
    }

    private function getValidLocale()
    {
        return (bool) !$this->translationData && in_array(request()->locale, config('translatable.locales')) ? request()->locale : config('translatable.fallback_locale');
    }

    private function getValidXlocale()
    {
        return in_array(request()->header('X-locale'), config('translatable.locales')) ? request()->header('X-locale') : config('translatable.fallback_locale');
    }
}
