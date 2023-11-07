<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $request->user()->update($request->only('name'));

        $request->user()->refresh();

        return \Modules\Customer\Transformers\CustomerResource::make($request->user());
    }


    public function profile(Request $request)
    {
        return \Modules\Customer\Transformers\CustomerResource::make($request->user());
    }

}
