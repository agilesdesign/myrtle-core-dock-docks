<?php

namespace Myrtle\Core\Docks\Http\Requests;

use Myrtle\Docks\Dock;
use Myrtle\Permissions\Models\Ability;
use Illuminate\Foundation\Http\FormRequest;

class SaveDockPermissionsForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Handle invoked form process
     *
     * @return mixed
     */
    public function process()
    {
        $method = debug_backtrace()[1]['function'];

        return call_user_func_array([$this, $method], func_get_args());
    }

    /**
     * Process updates dock permissions
     * @param Dock $dock
     */
    public function update(Dock $dock)
    {
        $names = $dock->abilities->transform(function ($ability, $key) {
            return str_replace('.', '_', $ability->name);
        })->toArray();

        $baseline = $dock->abilities->keyBy(function ($ability, $key) {
            return str_replace('.', '_', $ability->name);
        })->transform(function () use ($dock) {
            return collect(Dock::PERMISSIONABLE_TYPES)->keyBy(function ($type, $key) {
                return $type;
            })->transform(function () {
                return [];
            })->toArray();
        });

        $form = collect($this->only($names));

        $combine = collect(array_replace_recursive($form->toArray(), $baseline->toArray()));

        $combine->keyBy(function ($object, $ability) {
            return str_replace('_', '.', $ability);
        })->each(function ($type, $ability) {
            collect($type)->each(function ($objects, $key) use ($ability) {
                $ability = Ability::where('name', '=', $ability);
                $ability->first()->{$key}()->sync($objects ?? []);
            });
        });
    }
}
