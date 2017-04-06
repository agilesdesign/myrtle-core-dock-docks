<?php

namespace Myrtle\Core\Docks\Http\Requests;

use Myrtle\Core\Docks\Dock;
use Myrtle\Permissions\Models\Ability;
use Illuminate\Foundation\Http\FormRequest;

class SaveDockSettingsForm extends FormRequest
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
     * Process updates dock settings
     * @param Dock $dock
     */
    public function update(Dock $dock)
    {
        foreach ($this->except(['_token', '_method']) as $key => $value) {
            $dock->setSetting($key, $value);
        }

        $dock->storeSettings();
    }
}
