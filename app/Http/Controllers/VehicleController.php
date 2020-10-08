<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class VehicleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(Request $request)
    {
        $limit = min(intval($request->get('limit', 10)), 1000);

        $query = Vehicle::select('*');

        $search = $request->input('search');

        if (!empty($search))
        {
            $query = $query->where(function ($q) use ($search)
            {
                $q->orwhere('name', 'LIKE', '%' . $search . '%');
                $q->orwhere('location', 'LIKE', '%' . $search . '%');
            });
        }

        return $this->respondPagination($request, $query->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->input();

        $validator = Validator::make($input, [
            'name' => 'required|min:2',
            'location' => 'required|min:2',
            'bore' => 'required|numeric',
            'stroke' => 'required|numeric',
            'cylinders' => 'required|numeric',
            'engine_displacement' => 'required|numeric',
            'engine_power' => 'required|numeric',
            'price' => 'required|numeric',
            'displacement_unit' => 'required|numeric',
        ]);

        if ($validator->fails())
            return response()->json($validator->errors(), 422);

        DB::beginTransaction();

        try
        {
            if($input['displacement_unit'] == Vehicle::UNIT_CUBIC_CM)
                $input['engine_displacement'] = pow($input['bore'],2) * 0.7854 * $input['stroke'] * 0.001 * $input['cylinders'];
            else if($input['displacement_unit'] == Vehicle::UNIT_CUBIC_INCH)
                $input['engine_displacement'] = pow($input['bore'],2) * 0.7854 * $input['stroke'] * $input['cylinders'];

            $vehicle = Vehicle::create($input);

            $vehicle = $vehicle->fresh();

            DB::commit();

            return $this->respondCreated($vehicle->toArray());
        }
        catch (Exception $exception)
        {
            DB::rollBack();
            return $this->respondInternalError($message = 'Internal Server Error!', $errors = $exception);
        }

    }
}
