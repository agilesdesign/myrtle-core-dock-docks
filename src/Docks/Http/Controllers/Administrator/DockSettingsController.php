<?php

namespace Myrtle\Core\Docks\Http\Controllers\Administrator;

use Myrtle\Docks\Dock;
use Flasher\Support\Notifier;
use App\Http\Controllers\Controller;
use Myrtle\Docks\Http\Requests\SaveDockSettingsForm;

class DockSettingsController extends Controller
{
    /**
     * Show the edit form for dock settings
     *
     * @param Dock $dock
     * @return Response
     */
    public function edit(Dock $dock)
    {
        $this->authorize(get_class($dock) . '.edit-settings');

        return view('admin::docks.' . $dock->name . '.settings.edit')->withDock($dock);
    }

    /**
     * Update the dock settings
     *
     * @param SaveDockSettingsForm $form
     * @param Dock $dock
     * @return Response
     */
    public function update(SaveDockSettingsForm $form, Dock $dock)
    {
        $this->authorize(get_class($dock) . '.edit-settings');

        $form->process($dock);

        flasher()->alert($dock->name . ' settings updated successfully', Notifier::SUCCESS);

        return redirect()->route('admin.docks.index');
    }
}
