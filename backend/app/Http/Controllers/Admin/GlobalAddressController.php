<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalAddress;
use Illuminate\Http\Request;

class GlobalAddressController extends Controller
{
    public function index(Request $request)
    {
        $query = GlobalAddress::query();

        // 1. Division Filter (supports single or array)
        if ($request->filled('division')) {
            $divisions = is_array($request->division) ? $request->division : explode(',', $request->division);
            $divisions = array_filter(array_map('trim', $divisions));
            if (!empty($divisions)) {
                $query->whereIn('division', $divisions);
            }
        }

        // 2. District Filter (supports single or array)
        if ($request->filled('district')) {
            $districts = is_array($request->district) ? $request->district : explode(',', $request->district);
            $districts = array_filter(array_map('trim', $districts));
            if (!empty($districts)) {
                $query->whereIn('district', $districts);
            }
        }

        // 3. Upazila Filter (supports single or array)
        if ($request->filled('upazila')) {
            $upazilas = is_array($request->upazila) ? $request->upazila : explode(',', $request->upazila);
            $upazilas = array_filter(array_map('trim', $upazilas));
            if (!empty($upazilas)) {
                $query->whereIn('upazila', $upazilas);
            }
        }

        // 4. Pourashava/Union/City Corporation Filter (supports single or array)
        if ($request->filled('pourashava')) {
            $rawPours = is_array($request->pourashava) ? $request->pourashava : explode(',', $request->pourashava);
            $rawPours = array_filter(array_map('trim', $rawPours));
            if (!empty($rawPours)) {
                $expandedPours = [];
                foreach ($rawPours as $p) {
                    $expandedPours[] = $p;
                    if ($p === 'City Corporation' || $p === '0') {
                        $expandedPours[] = 'City Corporation';
                        $expandedPours[] = '0';
                    } elseif ($p === 'Pourashava' || $p === '1') {
                        $expandedPours[] = 'Pourashava';
                        $expandedPours[] = '1';
                    } elseif ($p === 'Union' || $p === '2') {
                        $expandedPours[] = 'Union';
                        $expandedPours[] = '2';
                    }
                }
                $query->whereIn('pourashava', array_values(array_unique($expandedPours)));
            }
        }

        // 5. Ward Filter (supports single or array)
        if ($request->filled('ward')) {
            $wards = is_array($request->ward) ? $request->ward : explode(',', $request->ward);
            $wards = array_filter(array_map('trim', $wards));
            if (!empty($wards)) {
                $expandedWards = [];
                foreach ($wards as $w) {
                    $expandedWards[] = $w;
                    $wClean = preg_replace('/[^0-9]/', '', $w);
                    if ($wClean !== '') {
                        $expandedWards[] = $wClean;
                        $expandedWards[] = 'Ward ' . $wClean;
                        $expandedWards[] = 'ward ' . $wClean;
                    }
                }
                $query->whereIn('ward', array_values(array_unique($expandedWards)));
            }
        }

        // Free Keyword Search (across all columns)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('upazila', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%")
                  ->orWhere('pourashava', 'like', "%{$search}%")
                  ->orWhere('ward', 'like', "%{$search}%")
                  ->orWhere('post_code', 'like', "%{$search}%");
            });
        }

        $addresses = $query->orderBy('division')
                           ->orderBy('district')
                           ->orderBy('upazila')
                           ->orderBy('ward')
                           ->paginate(25)
                           ->withQueryString();

        // 1. All Divisions
        $divisions = GlobalAddress::select('division')->distinct()->orderBy('division')->pluck('division');

        // 2. Districts (narrowed by selected division if given, otherwise all 64)
        $districtQuery = GlobalAddress::select('district')->distinct()->orderBy('district');
        if ($request->filled('division')) {
            $selDivs = is_array($request->division) ? $request->division : explode(',', $request->division);
            $districtQuery->whereIn('division', array_filter(array_map('trim', $selDivs)));
        }
        $districts = $districtQuery->pluck('district');

        // 3. Upazilas (narrowed by selected district or division if given, otherwise all)
        $upazilaQuery = GlobalAddress::select('upazila')->distinct()->orderBy('upazila');
        if ($request->filled('district')) {
            $selDists = is_array($request->district) ? $request->district : explode(',', $request->district);
            $upazilaQuery->whereIn('district', array_filter(array_map('trim', $selDists)));
        } elseif ($request->filled('division')) {
            $selDivs = is_array($request->division) ? $request->division : explode(',', $request->division);
            $upazilaQuery->whereIn('division', array_filter(array_map('trim', $selDivs)));
        }
        $upazilas = $upazilaQuery->pluck('upazila');

        // 4. Pourashavas (City Corporation, Pourashava, Union)
        $defaultPourashavas = ['City Corporation', 'Pourashava', 'Union'];
        $dbPourashavas = GlobalAddress::select('pourashava')->distinct()
            ->whereNotNull('pourashava')
            ->where('pourashava', '!=', '')
            ->pluck('pourashava')
            ->map(function ($val) {
                return match ((string)$val) {
                    '0' => 'City Corporation',
                    '1' => 'Pourashava',
                    '2' => 'Union',
                    default => $val,
                };
            })
            ->filter()
            ->values()
            ->toArray();
        $pourashavas = array_values(array_unique(array_merge($defaultPourashavas, $dbPourashavas)));
        sort($pourashavas);

        // 5. Wards (Natural sort order: Ward 1, Ward 2, ..., Ward 65)
        $rawWards = GlobalAddress::select('ward')->distinct()
            ->whereNotNull('ward')
            ->where('ward', '!=', '')
            ->pluck('ward')
            ->toArray();
        natsort($rawWards);
        $formattedWards = array_map(function ($w) {
            $tw = trim((string)$w);
            if (preg_match('/^(?:ward\s*)?(\d+.*)$/i', $tw, $m)) {
                return 'Ward ' . $m[1];
            }
            return str_starts_with(strtolower($tw), 'ward') ? $tw : 'Ward ' . $tw;
        }, array_values($rawWards));
        $wards = array_values(array_unique($formattedWards));
        natsort($wards);
        $wards = array_values($wards);

        $hierarchy = $this->getHierarchy();

        return view('admin.global_addresses.index', compact(
            'addresses',
            'divisions',
            'districts',
            'upazilas',
            'pourashavas',
            'wards',
            'hierarchy'
        ));
    }

    public function edit($globalAddress)
    {
        $globalAddress = $globalAddress instanceof GlobalAddress ? $globalAddress : GlobalAddress::findOrFail($globalAddress);
        $hierarchy = $this->getHierarchy();
        return view('admin.global_addresses.edit', compact('globalAddress', 'hierarchy'));
    }

    public function getHierarchy(): array
    {
        $divisions = [
            ['id' => '1', 'name' => 'Chattagram'],
            ['id' => '2', 'name' => 'Rajshahi'],
            ['id' => '3', 'name' => 'Khulna'],
            ['id' => '4', 'name' => 'Barisal'],
            ['id' => '5', 'name' => 'Sylhet'],
            ['id' => '6', 'name' => 'Dhaka'],
            ['id' => '7', 'name' => 'Rangpur'],
            ['id' => '8', 'name' => 'Mymensingh']
        ];

        $allDistricts = districtView();
        $allUpazilas = upazilasView();

        $allDivisions = array_column($divisions, 'name');
        sort($allDivisions);

        $divMap = [];
        $districtsByDivision = [];
        foreach ($divisions as $d) {
            $divMap[$d['id']] = $d['name'];
            $districtsByDivision[$d['name']] = [];
        }

        $allDistrictsList = [];
        $distMap = [];
        $upazilasByDistrict = [];
        $districtToDivision = [];

        foreach ($allDistricts as $dst) {
            $divName = $divMap[$dst['division_id']] ?? null;
            if ($divName) {
                $distName = $dst['name'];
                $districtsByDivision[$divName][] = $distName;
                $allDistrictsList[] = $distName;
                $distMap[$dst['id']] = ['div' => $divName, 'dist' => $distName];
                $upazilasByDistrict[$distName] = [];
                $districtToDivision[$distName] = $divName;
            }
        }

        $allUpazilasList = [];
        $upazilaToDistrict = [];

        foreach ($allUpazilas as $upz) {
            $distInfo = $distMap[$upz['district_id']] ?? null;
            if ($distInfo) {
                $dstName = $distInfo['dist'];
                $upzName = $upz['name'];
                $upazilasByDistrict[$dstName][] = $upzName;
                $allUpazilasList[] = $upzName;
                $upazilaToDistrict[$upzName] = $dstName;
            }
        }

        // Also merge any from GlobalAddress or Shop tables
        $dbGAs = GlobalAddress::select('division', 'district', 'upazila')
            ->distinct()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->whereNotNull('upazila')
            ->where('upazila', '!=', '')
            ->get();

        foreach ($dbGAs as $ga) {
            $div = is_numeric($ga->division) ? divisionname($ga->division) : trim($ga->division);
            $dist = is_numeric($ga->district) ? districtname($ga->district) : trim($ga->district);
            $upz = is_numeric($ga->upazila) ? upazilaname($ga->upazila) : trim($ga->upazila);

            if ($dist && $upz) {
                if ($div && !isset($districtsByDivision[$div])) {
                    $districtsByDivision[$div] = [];
                }
                if ($div && !in_array($dist, $districtsByDivision[$div])) {
                    $districtsByDivision[$div][] = $dist;
                }
                if (!isset($upazilasByDistrict[$dist])) {
                    $upazilasByDistrict[$dist] = [];
                }
                if (!in_array($upz, $upazilasByDistrict[$dist])) {
                    $upazilasByDistrict[$dist][] = $upz;
                }
                if (!in_array($upz, $allUpazilasList)) {
                    $allUpazilasList[] = $upz;
                }
                if (!in_array($dist, $allDistrictsList)) {
                    $allDistrictsList[] = $dist;
                }
                if ($div) {
                    $districtToDivision[$dist] = $div;
                }
                $upazilaToDistrict[$upz] = $dist;
            }
        }

        $allDistrictsList = array_values(array_unique($allDistrictsList));
        $allUpazilasList = array_values(array_unique($allUpazilasList));
        sort($allDistrictsList);
        sort($allUpazilasList);

        foreach ($districtsByDivision as &$dList) {
            $dList = array_values(array_unique($dList));
            sort($dList);
        }
        foreach ($upazilasByDistrict as &$uList) {
            $uList = array_values(array_unique($uList));
            sort($uList);
        }

        $rawWards = GlobalAddress::select('ward')->distinct()
            ->whereNotNull('ward')
            ->where('ward', '!=', '')
            ->pluck('ward')
            ->toArray();
        natsort($rawWards);
        $formattedWards = array_map(function ($w) {
            $tw = trim((string)$w);
            if (preg_match('/^(?:ward\s*)?(\d+.*)$/i', $tw, $m)) {
                return 'Ward ' . $m[1];
            }
            return str_starts_with(strtolower($tw), 'ward') ? $tw : 'Ward ' . $tw;
        }, array_values($rawWards));
        $wards = array_values(array_unique($formattedWards));
        natsort($wards);
        $wards = array_values($wards);

        $defaultPourashavas = ['City Corporation', 'Pourashava', 'Union'];
        $dbPourashavas = GlobalAddress::select('pourashava')->distinct()
            ->whereNotNull('pourashava')
            ->where('pourashava', '!=', '')
            ->pluck('pourashava')
            ->map(function ($val) {
                return match ((string)$val) {
                    '0' => 'City Corporation',
                    '1' => 'Pourashava',
                    '2' => 'Union',
                    default => $val,
                };
            })
            ->filter()
            ->values()
            ->toArray();
        $pourashavas = array_values(array_unique(array_merge($defaultPourashavas, $dbPourashavas)));
        sort($pourashavas);

        return [
            'divisions'           => $allDivisions,
            'districtsByDivision' => $districtsByDivision,
            'upazilasByDistrict'  => $upazilasByDistrict,
            'districtToDivision'  => $districtToDivision,
            'upazilaToDistrict'   => $upazilaToDistrict,
            'allDistricts'        => $allDistrictsList,
            'allUpazilas'         => $allUpazilasList,
            'pourashavas'         => $pourashavas,
            'wards'               => $wards,
        ];
    }

    public function cascadeOptions(Request $request)
    {
        $division = $request->query('division');
        $district = $request->query('district');

        $data = [];

        if ($division) {
            $data['districts'] = GlobalAddress::where('division', $division)
                ->select('district')->distinct()->orderBy('district')->pluck('district');
        } else {
            $data['districts'] = GlobalAddress::select('district')->distinct()->orderBy('district')->pluck('district');
        }

        if ($district) {
            $data['upazilas'] = GlobalAddress::where('district', $district)
                ->select('upazila')->distinct()->orderBy('upazila')->pluck('upazila');
        } elseif ($division) {
            $data['upazilas'] = GlobalAddress::where('division', $division)
                ->select('upazila')->distinct()->orderBy('upazila')->pluck('upazila');
        } else {
            $data['upazilas'] = GlobalAddress::select('upazila')->distinct()->orderBy('upazila')->pluck('upazila');
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division'        => 'required|string|max:100',
            'district'        => 'required|string|max:100',
            'upazila'         => 'required|string|max:100',
            'pourashava'      => 'nullable|string|max:100',
            'ward'            => 'nullable|string|max:100',
            'post_code'       => 'nullable|string|max:20',
            'delivery_charge' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        GlobalAddress::create($validated);

        return back()->with('success', 'Global Address / Delivery Zone added successfully.');
    }

    public function update(Request $request, $globalAddress)
    {
        $globalAddress = $globalAddress instanceof GlobalAddress ? $globalAddress : GlobalAddress::findOrFail($globalAddress);

        $validated = $request->validate([
            'division'        => 'required|string|max:100',
            'district'        => 'required|string|max:100',
            'upazila'         => 'required|string|max:100',
            'pourashava'      => 'nullable|string|max:100',
            'ward'            => 'nullable|string|max:100',
            'post_code'       => 'nullable|string|max:20',
            'delivery_charge' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $globalAddress->update($validated);

        $target = $request->is('super_admin/*') ? url('/super_admin/global_address') : route('admin.global_address.index');
        return redirect($target)->with('success', 'Global Address updated successfully.');
    }

    public function destroy(GlobalAddress $globalAddress)
    {
        $globalAddress->delete();
        return back()->with('success', 'Address zone deleted successfully.');
    }
}
