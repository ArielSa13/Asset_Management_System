<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Location;
use App\Services\ActivityLogService;

class LocationController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog
    ) {}

    public function index()
    {
        $locations = Location::withCount('assets')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(StoreLocationRequest $request)
    {
        $location = Location::create($request->validated());

        $this->activityLog->log('create', 'location', "Created location: {$location->name}");

        return redirect()->route('admin.locations.index')
            ->with('success', 'Lokasi berhasil dibuat.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->validated());

        $this->activityLog->log('update', 'location', "Updated location: {$location->name}");

        return redirect()->route('admin.locations.index')
            ->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        if ($location->assets()->exists()) {
            return back()->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki asset.');
        }

        $this->activityLog->log('delete', 'location', "Deleted location: {$location->name}");
        $location->delete();

        return redirect()->route('admin.locations.index')
            ->with('success', 'Lokasi berhasil dihapus.');
    }
}
