<?php

namespace Myrtle\Core\Docks\Http\Controllers\Administrator;

use Myrtle\Core\Docks\Dock;
use Illuminate\Http\Response;
use Myrtle\Core\Docks\Facades\Docks;
use App\Http\Controllers\Controller;
use Myrtle\Core\Docks\Policies\DocksDockPolicy;

class DocksController extends Controller
{
    /**
     * Show the list of docks
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize('view', DocksDockPolicy::class);

        $docks = Docks::all();

        return view('admin::docks.index')->withDocks($docks);
    }

    /**
     * Enable a Dock
     *
     * @param Dock $dock
     * @return Response
     */
    public function enable(Dock $dock)
    {
        $this->authorize('enabled', DocksDockPolicy::class);

        $dock->enable();

        return redirect()->route('admin.docks.index');
    }

    /**
     * Disable a Dock
     *
     * @param Dock $dock
     * @return Response
     */
    public function disabled(Dock $dock)
    {
        $this->authorize('disable', DocksDockPolicy::class);

        $dock->disable();

        return redirect()->route('admin.docks.index');
    }
}
