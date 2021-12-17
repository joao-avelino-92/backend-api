<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rules;
use Illuminate\Http\Request;
use App\Http\Resources\RulesResource;
use App\Models\Enums\RulesEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = Rules::all();
        return response(['rules' => RulesResource::collection($rules), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $userRule = Rules::where('rule', '1')->where('user_id', Auth::User()->id)->first();
        dd($userRule);

        if (!$userRule) {
            return response(['message' => 'User does not have registered rule']);
        }

        if ($userRule->rule != 1) {
            return response(['message' => 'User does not have permission to use this feature']);
        }

        $validator = Validator::make($data, [
            'rule' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $rule = Rules::create($data);

        return response(['ceo' => new RulesResource($rule), 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function show(Rules $rule)
    {
        return response(['ceo' => new RulesResource($rule), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rules $rule)
    {
        $rule->update($request->all());

        $userRule = Rules::where('user_id', Auth::User()->id)->first();

        if (!$userRule) {
            return response(['message' => 'User does not have registered rule']);
        }

        if ($userRule->rule != 1) {
            return response(['message' => 'User does not have permission to use this feature']);
        }

        return response(['rule' => new RulesResource($rule), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rules  $rules
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rules $rule)
    {
        $rule->delete();

        return response(['message' => 'Deleted']);
    }
}
