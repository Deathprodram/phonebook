<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function __construct()
    {
        $this->phone = Phone::where('enabled', 1);
    }

    public function getPhoneList(Request $request) {
        $count = $request->count ?? 10;
        $page = $request->page ?? 1;
        $offset = (int)$page * (int)$count - $count;
        $sort_by = $request->sort_by ?? null;
        $data['data_count'] = $this->phone->count();
        $phone_list = $this->phone->select('phones.*')
            ->with(['user', 'country'])
            ->leftJoin('users', 'phones.user_id', '=', 'users.id')
            ->leftJoin('countries', 'phones.country_id', '=', 'countries.id')
            ->offset($offset)
            ->limit($count);

        if ($sort_by) {
            if ($sort_by[0] == 'user') $phone_list->orderBy('users.name', $sort_by[1]);
            elseif ($sort_by[0] == 'country') $phone_list->orderBy('countries.name', $sort_by[1]);
        } else $phone_list->orderBy('phones.created_at', 'desc');

        if ($request->search) {
            if ($request->search[0] == 'phone') $searchKey = 'phones.phone';
            elseif ($request->search[0] == 'user') $searchKey = 'users.name';
            elseif ($request->search[0] == 'country') $searchKey = 'countries.name';
            $phone_list->where($searchKey, 'like', '%'.$request->search[1].'%');
        }
        $data['phone_list'] = $phone_list->get();
        return $data;
    }

    public function createPhoneData(Request $request) {
        $user = new User();
        $user->name = $request->name;
        $user->save();
        foreach ($request->phoneData as $data) {
            Phone::create([
                'user_id' => $user->id,
                'country_id' => $data['country_id'],
                'phone' => $data['phone'],
            ]);
        }
        return ['message' => true];
    }

    public function editPhoneData(Request $request) {
        $phone = Phone::find($request->id);
        $phone->user->name = $request->name ?? $phone->user->name;
        $phone->user->save();
        $phone->country_id = $data[0]['country_id'] ?? $phone->country_id;
        $phone->phone = $data[0]['phone'] ?? $phone->phone;
        $phone->save();
        return ['message' => true];
    }

    public function destroyPhoneData(Request $request) {
        Phone::find((int) $request->id)->delete();
        return ['message' => true];
    }

}
