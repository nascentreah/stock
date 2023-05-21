<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\StoreAsset;
use App\Http\Requests\Backend\UpdateAsset;
use App\Models\Asset;
use App\Models\Currency;
use App\Models\Market;
use App\Models\Sort\Backend\AssetSort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = new AssetSort($request);

        $assets = Asset::select('assets.*', 'markets.code AS market_code')
            ->join('markets', 'markets.id', '=', 'assets.market_id') // inner join is required to enable sort by market, otherwise with() would have been sufficient
            ->with('currency:id,code,symbol_native')
            ->orderBy($sort->getSortColumn(), $sort->getOrder())->paginate($this->rowsPerPage);

        return view('pages.backend.assets.index', [
            'assets'    => $assets,
            'sort'      => $sort->getSort(),
            'order'     => $sort->getOrder(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.backend.assets.create', [
            'currencies'    => Currency::all(),
            'markets'       => Market::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAsset $request)
    {
        $asset              = new Asset();
        $asset->market()->associate($request->market);
        $asset->currency()->associate($request->currency);
        $asset->symbol      = $request->symbol;
        $asset->symbol_ext  = $request->symbol_ext;
        $asset->name        = $request->name;
        $asset->price       = $request->price;
        $asset->change_abs  = $request->change_abs;
        $asset->change_pct  = $request->change_pct;
        $asset->volume      = $request->volume;
        $asset->supply      = $request->supply;
        $asset->market_cap  = $request->market_cap;
        $asset->status      = Asset::STATUS_ACTIVE;

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $imageFileName = time() . '-' . $request->symbol . '.' . $image->getClientOriginalExtension();
            $imageContents = (string) Image::make($image)
                ->resize(null, config('settings.asset_logo_thumb_height'), function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode();

            // store logo
            if (Storage::put('assets/' . $imageFileName, $imageContents)) {
                // set uploaded logo
                $asset->logo = $imageFileName;
            }
        }

        $asset->save();

        return redirect()
            ->route('backend.assets.index')
            ->with('success', __('app.asset_saved', ['name' => $asset->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Asset $asset
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Asset $asset)
    {
        return view('pages.backend.assets.edit', [
            'asset'         => $asset,
            'currencies'    => Currency::all(),
            'markets'       => Market::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAsset $request, Asset $asset)
    {
        $asset->market()->associate($request->market);
        $asset->currency()->associate($request->currency);
        $asset->symbol      = $request->symbol;
        $asset->symbol_ext  = $request->symbol_ext;
        $asset->name        = $request->name;
        $asset->price       = $request->price;
        $asset->change_abs  = $request->change_abs;
        $asset->change_pct  = $request->change_pct;
        $asset->volume      = $request->volume;
        $asset->supply      = $request->supply;
        $asset->market_cap  = $request->market_cap;
        $asset->status      = $request->status;

        // logo is uploaded or updated
        if ($request->hasFile('logo')) {
//            dd('xx');
            $image = $request->file('logo');
            $imageFileName = time() . '-' . $request->symbol . '.' . $image->getClientOriginalExtension();
            $imageContents = (string) Image::make($image)
                ->resize(null, config('settings.asset_logo_thumb_height'), function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode();

            // store logo
            if (Storage::put('assets/' . $imageFileName, $imageContents)) {
                // delete previous logo
                if ($asset->logo)
                    Storage::delete('assets/' . $asset->logo);
                // set uploaded logo
                $asset->logo = $imageFileName;
            }
        // logo is deleted
        } else if ($request->deleted === 'true' && $asset->logo) {
            Storage::delete('assets/' . $asset->logo);
            $asset->logo = NULL;
        }

        $asset->save();

        return redirect()
            ->route('backend.assets.index')
            ->with('success', __('app.asset_saved', ['name' => $asset->name]));
    }



    /**
     * Delete asset confirmation page
     *
     * @param Request $request
     * @param Asset $asset
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Asset $asset) {
        $request->session()->flash('warning', __('app.asset_delete_warning'));
        return view('pages.backend.assets.delete', ['asset' => $asset]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asset $asset)
    {
        $assetName = $asset->name;

        // delete logo
        if ($asset->logo)
            Storage::delete('assets/' . $asset->logo);

        // delete asset
        $asset->delete();

        return redirect()
            ->route('backend.assets.index')
            ->with('success', __('app.asset_deleted', ['name' => $assetName]));
    }
}
