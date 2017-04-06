<?php

namespace Myrtle\Core\Docks\Http\Controllers\Administrator;

use Myrtle\Core\Docks\Dock;
use Flasher\Support\Notifier;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Myrtle\Core\Docks\Http\Requests\SaveDockPermissionsForm;

class DockPermissionsController extends Controller
{
    /**
     * Show the edit form for dock permissions
     *
     * @param Dock $dock
     * @return Response
     */
    public function edit(Dock $dock)
    {
        $this->authorize(get_class($dock) . '.edit-permissions');

        return view('admin::docks.' . $dock->name . '.permissions.edit')->withDock($dock);
    }

    /**
     * Update the dock permissions
     *
     * @param SaveDockPermissionsForm $form
     * @param Dock $dock
     * @return Response
     */
    public function update(SaveDockPermissionsForm $form, Dock $dock)
    {
        $this->authorize(get_class($dock) . '.edit-permissions');

        $form->process($dock);

        flasher()->alert($dock->name . ' dock permissions updated successfully', Notifier::SUCCESS);

        return redirect()->route('admin.docks.index');
    }
}
