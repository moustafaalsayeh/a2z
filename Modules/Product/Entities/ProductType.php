<?php

namespace Modules\Product\Entities;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Helpers\Translatable;
use Modules\Media\Helpers\Mediable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Modules\Review\Entities\Reviewable;

class ProductType extends Model
{
    use Translatable, Mediable;

    protected $guarded = ['id'];

    public $timestamps = false;

    public $translatedAttributes = [
        'name',
        'description'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $product_type_id = ProductType::whereHas('translations', function ($query) {
                $query->where('name', 'Default product type');
            })->first()->id;

            if($model->id == $product_type_id)
            {
                throw ValidationException::withMessages(['product_type' => __('fail_action', ['model' => __('product_type'), 'action' => __('delete')])]);
            }

            foreach ($model->products as $key => $product) {
                $product->update(['product_type_id' => $product_type_id]);
            }

            $model->deleteAllMedia();
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function rootParent()
    {
        if(!$this->parent)
        {
            return $this;
        }

        $parent = $this->parent;

        return $parent->rootParent();
    }
    public function children()
    {
        return $this->hasMany(ProductType::class);
    }

    public function allChildren()
    {
       return $this->children()->with('allChildren');
    }

    public function reviewItems()
    {
        $this->morphToMany(Reviewable::class, 'reviewable');
    }

    public function getLeavesTypesAttribute()
    {
        /**
         * this function get the last layer product_types of certain product type
         * prosedure:
         * 1-get children and children of children and so on
         * 2-flatten the returned array
         * 3-for each element in the array:
         *   3.1-if element key contains .id
         *      3.1.1- add to to the leaves_ids as index equal to the addition of all numerics in the key. to make sure the leave element overwrite parent element
         *      3.1.1-(EDIT) add to to the leaves_ids as NEW index. to search in all the children not only the leaves
         */
        $all_children = $this->allChildren->toArray();
        $all_children = Arr::dot($all_children);

        if(!$all_children)
        {
            return array($this->id);
        }

        $leaves_ids = [$this->id];

        foreach ($all_children as $key => $child) {

            // $key_array = explode(".", $key);
            // $index = 0;
            // for ($i=0; $i < count($key_array); $i+=2) {
            //     $index += (int) $key_array[$i];
            // }

            if(Str::contains($key, '.id'))
            {
                // $leaves_ids[$index] = $child;
                $leaves_ids[] = $child;
            }
        }

        return $leaves_ids;
    }

    public function getDirectChildrenCountAttribute()
    {
        return $this->children->count();
    }

    public function getAllChildrenCountAttribute()
    {
        $all_children = Arr::dot($this->allChildren->toArray());

        $all_children_count = 0;

        foreach ($all_children as $key => $child) {
            if (Str::contains($key, '.id')) {
                $all_children_count += 1;
            }
        }

        return $all_children_count;
    }

    public function getLevelAttribute()
    {
        $level = 0;
        $current_product_type = $this;

        while ($current_product_type = $current_product_type->parent) {
            $level++;
        }

        return $level;
    }

    public function getIsLeafAttribute()
    {
        return $this->children->count() ? true : false;
    }
}
