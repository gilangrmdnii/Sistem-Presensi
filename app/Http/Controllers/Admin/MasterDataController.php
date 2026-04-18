<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    public function division(Request $request)
    {
        return $this->simpleMaster($request, [
            'model' => Division::class,
            'title' => 'Divisi',
            'route' => 'admin.masters.division',
            'fields' => [['name' => 'name', 'label' => 'Nama Divisi', 'rules' => ['required', 'string', 'max:255']]],
        ]);
    }

    public function jobTitle(Request $request)
    {
        return $this->simpleMaster($request, [
            'model' => JobTitle::class,
            'title' => 'Jabatan',
            'route' => 'admin.masters.job-title',
            'fields' => [['name' => 'name', 'label' => 'Nama Jabatan', 'rules' => ['required', 'string', 'max:255']]],
        ]);
    }

    public function education(Request $request)
    {
        return $this->simpleMaster($request, [
            'model' => Education::class,
            'title' => 'Pendidikan',
            'route' => 'admin.masters.education',
            'fields' => [['name' => 'name', 'label' => 'Jenjang Pendidikan', 'rules' => ['required', 'string', 'max:255']]],
        ]);
    }

    public function admin(Request $request)
    {
        $users = User::whereIn('role', [User::ROLE_ATASAN_DIVISI, User::ROLE_HRD])
            ->orderBy('role', 'desc')
            ->paginate(20);

        return view('admin.master-data.admin', compact('users'));
    }

    private function simpleMaster(Request $request, array $config)
    {
        $modelClass = $config['model'];

        if ($request->isMethod('POST')) {
            $rules = collect($config['fields'])->mapWithKeys(fn ($f) => [$f['name'] => $f['rules']])->toArray();
            $data = $request->validate($rules);

            if ($request->filled('_id')) {
                $modelClass::findOrFail($request->_id)->update($data);
                $flash = 'Data diperbarui.';
            } else {
                $modelClass::create($data);
                $flash = 'Data ditambahkan.';
            }
            return redirect()->route($config['route'])->with('flash.banner', $flash);
        }

        if ($request->isMethod('DELETE')) {
            $modelClass::findOrFail($request->_id)->delete();
            return redirect()->route($config['route'])->with('flash.banner', 'Data dihapus.');
        }

        $items = $modelClass::orderBy('name')->paginate(15);

        return view('admin.master-data.generic', [
            'items' => $items,
            'config' => $config,
        ]);
    }
}
