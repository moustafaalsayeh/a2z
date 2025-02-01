<?php

namespace Modules\CustomFields\Helpers;

use ReflectionClass;
use Modules\CustomFields\Entities\Text;
use Modules\CustomFields\Entities\TextField;

trait Textable
{
    public function textValues($type = null, $text_field_id)
    {
        return $this->morphMany(Text::class, 'textable')
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($text_field_id, function ($query) use ($text_field_id) {
                $query->where('text_field_id', $text_field_id);
            });
    }

    public function getTextFieldsAttribute()
    {
        $className = new ReflectionClass($this);

        $text_fields = TextField::where('textable_name', $className->getName())->get();
        // dd($text_fields);

        $text_fields_values = [];

        $text_fields->each(function ($item, $key) use(&$text_fields_values){
            $text_value = $this->textValues(null, $item->id)->get();

            $text_fields_values[$key]['text_field_id'] = $item->id;
            $text_fields_values[$key]['name'] = $item->name;
            $text_fields_values[$key]['type'] = $item->type;
            $text_fields_values[$key]['text_value_id'] = $text_value->isEmpty() ? null : $text_value->id ;
            $text_fields_values[$key]['value'] = $text_value->isEmpty() ? null : $text_value->value;
        });
        
        return $text_fields_values;
    }
}