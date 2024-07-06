<?php

namespace App\Http\Controllers;

use App\Http\Traits\HelperTrait;
use App\Models\Actual;
use App\Models\Predict;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PredictController extends Controller
{
    use HelperTrait;

    function dashboard()
    {
        $modelActual = Actual::all();
        $modelPredict = Predict::all();
        $modelUser = User::all();

        // daftar kecamatan
        $arrayKecamatan = [];
        // produksi per kecamatan
        $arrayProduksiKecamatan = [];

        // list tahun
        $arrayDate = [];
        // data actual
        $arrayActual = [];
        // data predict
        $arrayPredict = [];

        for ($n = 0; $n < count($modelActual); $n++) {
            if (!in_array($modelActual[$n]->kecamatan, $arrayKecamatan)) {
                $arrayKecamatan[] = $modelActual[$n]->kecamatan;
                $arrayProduksiKecamatan[] = $modelActual[$n]->produksi;
            } else {
                $index = array_search($modelActual[$n]->kecamatan, $arrayKecamatan);
                $arrayProduksiKecamatan[$index] += $modelActual[$n]->produksi;
            }

            $tahun = $modelActual[$n]->tahun->year ?? null;

            if (!in_array($tahun, $arrayDate)) {
                $arrayDate[] = $tahun;
                $arrayActual[] = $modelActual[$n]->produksi;
                // '?? 0' will return 0 if index out of range or value is null
                //$arrayPredict[] = $modelPredict[$n]->produksi_predict ?? 0;
            } else {
                $index = array_search($tahun, $arrayDate);
                $arrayActual[$index] += $modelActual[$n]->produksi;
            }
        }

        for ($n = 0; $n < count($modelPredict); $n++) {
            $tahun = $modelPredict[$n]->tahun->year ?? null;

            if (!in_array($tahun, $arrayDate)) {
                $arrayDate[] = $tahun;
                //$index = array_search($tahun, $arrayDate);
                $arrayPredict[] = $modelPredict[$n]->produksi_predict ?? 0;
                // safety get, will return null rather Exception
                $actual = $arrayActual[$n] ?? null;
                if ($actual == null){
                    $arrayActual[] = 0;
                }
            } else {
                $arrayPredict[] = $modelPredict[$n]->produksi_predict ?? 0;
                //dd($n, $tahun, $arrayDate, $arrayActual, $arrayPredict);
            }

        }

        // total data aktual, user
        $arrayTotalData = [count($modelActual), count($modelUser)];

        $sizeActual = count($arrayActual);
        $sizePredict = count($arrayPredict);
        // if user add new data actual but not doing predict yet
        // then the arrayActual and arrayPredict will have different length
        if($sizeActual != $sizePredict){
            // Pad the array with zero
            $arrayPredict = array_pad($arrayPredict, max($sizeActual, $sizePredict), 0);
        }

        return view('dashboard', [
            'array_kecamatan' => $arrayKecamatan,
            'array_produksi_kecamatan' => $arrayProduksiKecamatan,
            'array_total_data' => $arrayTotalData,
            'array_year' => $arrayDate,
            'array_actual' => $arrayActual,
            'array_predict' => $arrayPredict,
        ]);
    }

    function viewDataActual()
    {
        return $this->responseJson(Actual::getAll());
    }

    function crudDataActual(Request $request)
    {
        // dot change to underscore by Laravel self
        switch (true) {
            case $request->has('data_actual_store'):
                return $this->storeDataActual($request);
            case $request->has('data_actual_change'):
                return $this->changeDataActual($request);
            case $request->has('data_actual_erase'):
                return $this->eraseDataActual($request);
            default:
                return $this->responseJson(null, 'Not Found', 404);
        }
    }

    private function storeDataActual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|date_format:Y',
            'kecamatan' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01',
            'produksi' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $model = new Actual;
        $model->fill($validator->safe()->all());

        // Not a carbon instance
        $tahun = $validator->safe()->only('tahun')['tahun'];
        $carbon = Carbon::createFromFormat('Y', $tahun);
        // delete predict by year
        $this->deletePredictByYear($carbon);

        $result = $model->save();

        if ($result) {
            return back();
        } else {
            return $this->responseJson(null, 'Failed to store data', 500);
        }
    }

    private function changeDataActual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:App\Models\Actual,id',
            'tahun' => 'required|date_format:Y',
            'kecamatan' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric|min:0.01',
            'produksi' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $model = Actual::find($request->id);

        // Carbon instance
        $tahun = $model->tahun;
        // delete predict by year
        $this->deletePredictByYear($tahun);

        $model->fill($validator->safe()->all());
        $result = $model->save();

        if ($result) {
            return back();
        } else {
            return $this->responseJson(null, 'Failed to store data', 500);
        }
    }

    private function eraseDataActual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:App\Models\Actual,id',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $model = Actual::find($request->id);

        // Carbon instance
        $tahun = $model->tahun;
        // delete predict by year
        $this->deletePredictByYear($tahun);

        $result = $model->delete();

        if ($result) {
            return back();
        } else {
            return $this->responseJson(null, 'Failed to delete data', 500);
        }
    }

    private function deletePredictByYear($carbonInstance)
    {
        $predict = DB::table('predicts')
            ->whereRaw('year(tahun) >= ?', [$carbonInstance->year])
            ->delete();
    }

    function viewDataPredict()
    {
        $dataActual = DB::table('actuals')
            ->selectRaw('YEAR(tahun) as tahun, SUM(produksi) as produksi_actual')
            ->groupByRaw('YEAR(tahun)')
            ->get();

        $dataPredict = Predict::getAll();

        $data = [];

        $max = max(count($dataActual), count($dataPredict));

        // check if actual and predict has same year and same produksi
        for ($n = 0; $n < $max; $n++) {
            // initialize with null
            $tahun = $dataActual[$n]->tahun ?? null;
            $produksiActual = $dataActual[$n]->produksi_actual ?? null;

            if ($tahun == null){
                $tahun = $dataPredict[$n]->tahun->year ?? null;
            }

            if ($produksiActual == null){
                $produksiActual = $dataPredict[$n]->produksi_actual ?? null;
            }

            $data[$n] = [
                'tahun' => $tahun,
                'produksi_actual' => $produksiActual,
                'produksi_predict' => $dataPredict[$n]->produksi_predict ?? null,
            ];
        }

        return $this->responseJson($data);
    }

    function makePredict(Request $request)
    {
        // dot change to underscore by Laravel self
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'alpha' => 'required|numeric|min:0.01|max:1',
        ]);

        if ($validator->fails()) {
            return $this->responseJson(null, 'Data is not valid.', 422);
        }

        $dataKey = $validator->safe()->only('data');
        // need 2 record to predict
        if (count($dataKey['data']) <= 1) {
            return $this->responseJson(null, 'Need data at least 2 record.', 422);
        }
        $alphaKey = $validator->safe()->only('alpha');

        $resultArray = $this->processPredict(
            $alphaKey['alpha'],
            $dataKey['data']
        );

        // save to database
        foreach ($resultArray as $value) {
            $tahun = $value['tahun'];
            $tahunFormatted = Carbon::createFromFormat('Y', $tahun)
                ->format('Y-m-d');

            $dataPredict = DB::table('predicts')
                ->select('*')
                ->whereRaw('YEAR(tahun) = ?', [$tahun])
                ->get();

            // no data found
            if (count($dataPredict) == 0) {
                DB::table('predicts')
                    ->insert([
                        'tahun' => $tahunFormatted,
                        'produksi_actual' => $value['produksi_actual'],
                        'produksi_predict' => $value['produksi_predict'],
                    ]);
            } else {
                // do update rather than insert
                DB::table('predicts')
                    ->whereRaw('YEAR(tahun) = ?', [$tahun])
                    ->update([
                        'tahun' => $tahunFormatted,
                        'produksi_actual' => $value['produksi_actual'],
                        'produksi_predict' => $value['produksi_predict'],
                    ]);
            }

            /*Carbon::createFromFormat('Y', $value)
                ->format('Y-m-d');
            $model = Predict::find(20);
            $model->fill($value);
            $model->save();*/
        }

        return $this->responseJsonRaw([
            'data' => $resultArray,
            'message' => 'Predict Success'
        ]);
    }

    private function processPredict($alpha, $data)
    {
        $key = 'produksi_actual';
        $An = 0;
        $AnPrime = 0;
        $an = 0;
        $bn = 0;
        $predictions = [];

        $last_year = 0;
        $last_an = 0;
        $last_bn = 0;

        for ($n = 0; $n < count($data); $n++) {
            $actual = $data[$n][$key];
            if ($n == 0) {
                if($actual != null) {
                    $last_year = $data[$n]['tahun'];
                    // initialization
                    // A1 = actual[0],
                    $An = $actual;
                    // A"1 = actual[0]
                    $AnPrime = $actual;
                    // a1 = 0
                    $an = 0;
                    // b1 = 0
                    $bn = 0;

                    $predictions[$n] = [
                        'tahun' => $data[$n]['tahun'],
                        'produksi_actual' => $actual,
                        'produksi_predict' => null,
                    ];
                }
            } else {
                if($actual != null) {
                    $last_year = $data[$n]['tahun'];
                    // An = (alpha * actual[n]) + (1 - alpha) * An(old)
                    $An = ($alpha * $actual) + (1 - $alpha) * $An;
                    // A"n = (alpha * $An(now)) + (1 - alpha) * AnPrime(old)
                    $AnPrime = ($alpha * $An) + (1 - $alpha) * $AnPrime;
                    // an = (2 * An(now)) - AnPrime(now)
                    $an = (2 * $An) - $AnPrime;
                    // bn = (alpha / (1 - alpha)) * (An(now) - AnPrime(now))
                    $bn = ($alpha / (1 - $alpha)) * ($An - $AnPrime);
                    // nilai predict, Fn = an(now) + bn(now)
                    $Fn = $an + $bn;

                    $predictions[$n] = [
                        'tahun' => $data[$n]['tahun'],
                        'produksi_actual' => $actual,
                        'produksi_predict' => $Fn,
                    ];
                    $last_an = $an;
                    $last_bn = $bn;
                }
            }
        }

        if($last_year != 0) {
            $carbon = Carbon::createFromFormat('Y', $last_year);

            // predict nex year
            for ($n = 1; $n <= 5; $n++) {
                $carbon->addYear();

                $predictions[] = [
                    'tahun' => $carbon->year,
                    'produksi_actual' => null,
                    'produksi_predict' => $last_an + ($last_bn * $n),
                ];
            }
        }

        return $predictions;
    }

}
