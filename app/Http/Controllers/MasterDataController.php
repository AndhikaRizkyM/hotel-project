<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\FbMenu;
use App\Models\LaundryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MasterDataController extends Controller
{
    // ==========================================
    // 1. Users Management
    // ==========================================
    public function usersIndex()
    {
        $users = User::orderBy('name')->get();
        return view('master.users.index', compact('users'));
    }

    public function usersCreate()
    {
        $roles = \App\Models\Role::orderBy('id')->get();
        return view('master.users.create', compact('roles'));
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);

        return redirect()->route('master.users.index')->with('success', 'User created successfully.');
    }

    public function usersEdit($id)
    {
        $user = User::findOrFail($id);
        $roles = \App\Models\Role::orderBy('id')->get();
        return view('master.users.edit', compact('user', 'roles'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'string', 'exists:roles,name'],
            'password' => ['nullable', 'string', 'min:5'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('master.users.index')->with('success', 'User updated successfully.');
    }

    public function usersToggle($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return redirect()->route('master.users.index')->with('success', 'User status toggled successfully.');
    }

    // ==========================================
    // 2. Room Types Management
    // ==========================================
    public function roomTypesIndex()
    {
        $roomTypes = RoomType::all();
        return view('master.room-types.index', compact('roomTypes'));
    }

    public function roomTypesCreate()
    {
        return view('master.room-types.create');
    }

    public function roomTypesStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'size' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'facilities' => ['nullable', 'string'],
            'breakfast_included' => ['nullable', 'boolean'],
            'extra_bed_available' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
        ]);

        RoomType::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'size' => $request->size,
            'price_per_night' => $request->price_per_night,
            'facilities' => $request->facilities,
            'breakfast_included' => $request->has('breakfast_included') ? 1 : 0,
            'extra_bed_available' => $request->has('extra_bed_available') ? 1 : 0,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return redirect()->route('master.room-types.index')->with('success', 'Room Type created successfully.');
    }

    public function roomTypesEdit($id)
    {
        $roomType = RoomType::findOrFail($id);
        return view('master.room-types.edit', compact('roomType'));
    }

    public function roomTypesUpdate(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'size' => ['required', 'integer', 'min:1'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'facilities' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $roomType->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'size' => $request->size,
            'price_per_night' => $request->price_per_night,
            'facilities' => $request->facilities,
            'breakfast_included' => $request->has('breakfast_included') ? 1 : 0,
            'extra_bed_available' => $request->has('extra_bed_available') ? 1 : 0,
            'description' => $request->description,
        ]);

        return redirect()->route('master.room-types.index')->with('success', 'Room Type updated successfully.');
    }

    public function roomTypesDestroy($id)
    {
        $roomType = RoomType::findOrFail($id);
        $roomType->delete();
        return redirect()->route('master.room-types.index')->with('success', 'Room Type deleted successfully.');
    }

    // ==========================================
    // 3. Rooms Management
    // ==========================================
    public function roomsIndex()
    {
        $rooms = Room::with('roomType')->orderBy('room_number')->get();
        return view('master.rooms.index', compact('rooms'));
    }

    public function roomsCreate()
    {
        $roomTypes = RoomType::all();
        return view('master.rooms.create', compact('roomTypes'));
    }

    public function roomsStore(Request $request)
    {
        $request->validate([
            'room_number' => ['required', 'string', 'max:255', 'unique:rooms'],
            'floor' => ['required', 'integer', 'min:1'],
            'room_type_id' => ['required', 'exists:room_types,id'],
        ]);

        Room::create([
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'room_type_id' => $request->room_type_id,
            'status' => 'A',
            'is_active' => true,
        ]);

        return redirect()->route('master.rooms.index')->with('success', 'Room created successfully.');
    }

    public function roomsEdit($id)
    {
        $room = Room::findOrFail($id);
        $roomTypes = RoomType::all();
        return view('master.rooms.edit', compact('room', 'roomTypes'));
    }

    public function roomsUpdate(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_number' => ['required', 'string', 'max:255', 'unique:rooms,room_number,' . $room->id],
            'floor' => ['required', 'integer', 'min:1'],
            'room_type_id' => ['required', 'exists:room_types,id'],
            'status' => ['required', 'in:A,O,D,C,M,R,B'],
        ]);

        $room->update([
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'room_type_id' => $request->room_type_id,
            'status' => $request->status,
        ]);

        return redirect()->route('master.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function roomsToggle($id)
    {
        $room = Room::findOrFail($id);
        $room->update([
            'is_active' => !$room->is_active
        ]);

        return redirect()->route('master.rooms.index')->with('success', 'Room status toggled successfully.');
    }

    // ==========================================
    // 4. F&B Menus Management
    // ==========================================
    public function fbMenusIndex()
    {
        $menus = FbMenu::orderBy('type')->orderBy('name')->get();
        return view('master.fb-menus.index', compact('menus'));
    }

    public function fbMenusCreate()
    {
        return view('master.fb-menus.create');
    }

    public function fbMenusStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:food,beverage'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        FbMenu::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'is_active' => true,
        ]);

        return redirect()->route('master.fb-menus.index')->with('success', 'F&B Menu item created successfully.');
    }

    public function fbMenusEdit($id)
    {
        $menu = FbMenu::findOrFail($id);
        return view('master.fb-menus.edit', compact('menu'));
    }

    public function fbMenusUpdate(Request $request, $id)
    {
        $menu = FbMenu::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:food,beverage'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $menu->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
        ]);

        return redirect()->route('master.fb-menus.index')->with('success', 'F&B Menu item updated successfully.');
    }

    public function fbMenusToggle($id)
    {
        $menu = FbMenu::findOrFail($id);
        $menu->update([
            'is_active' => !$menu->is_active
        ]);

        return redirect()->route('master.fb-menus.index')->with('success', 'F&B Menu item toggled successfully.');
    }

    // ==========================================
    // 5. Laundry Services Management
    // ==========================================
    public function laundryServicesIndex()
    {
        $services = LaundryService::orderBy('name')->get();
        return view('master.laundry-services.index', compact('services'));
    }

    public function laundryServicesCreate()
    {
        return view('master.laundry-services.create');
    }

    public function laundryServicesStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        LaundryService::create([
            'name' => $request->name,
            'price' => $request->price,
            'is_active' => true,
        ]);

        return redirect()->route('master.laundry-services.index')->with('success', 'Laundry Service created successfully.');
    }

    public function laundryServicesEdit($id)
    {
        $service = LaundryService::findOrFail($id);
        return view('master.laundry-services.edit', compact('service'));
    }

    public function laundryServicesUpdate(Request $request, $id)
    {
        $service = LaundryService::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $service->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->route('master.laundry-services.index')->with('success', 'Laundry Service updated successfully.');
    }

    public function laundryServicesToggle($id)
    {
        $service = LaundryService::findOrFail($id);
        $service->update([
            'is_active' => !$service->is_active
        ]);

        return redirect()->route('master.laundry-services.index')->with('success', 'Laundry Service toggled successfully.');
    }
}
