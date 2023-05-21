<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Asset;
use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Sort\Frontend\AssetSort;
use App\Services\API\StockMarketHistoricalDataApi;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $sort = new AssetSort($request);

        $markets = Market::select('id','code','name','country_code')
            ->where('status', Market::STATUS_ACTIVE)
            ->orderBy('name', 'asc')
            ->get();

        $market = $request->market ?: config('settings.market');

        $assets = Asset::where('status', Asset::STATUS_ACTIVE)
            ->whereIn('market_id', function($query) use ($market) {
                $query->select('id')->from('markets')->where('code', $market);
            })
            ->with('currency:id,code,symbol_native')
            ->withCount('trades')
            ->orderBy($sort->getSortColumn(), $sort->getOrder())
            ->paginate($this->rowsPerPage);

        return view('pages.frontend.assets.index', [
            'selected_market'   => $market,
            'markets'           => $markets,
            'assets'            => $assets,
            'sort'              => $sort->getSort(),
            'order'             => $sort->getOrder(),
        ]);
    }

    /**
     * Search assets by name or symbol
     *
     * @param $query
     * @return array
     */
    public function search($query) {
        $query = strtolower($query);

        // title field is required so correct result value is passed to onSelect() callback (Semantic UI search)
        $assets = Asset::select('name', 'id AS value')
            ->where('status', Asset::STATUS_ACTIVE)
            ->where(function($sql) use($query) {
                $sql->whereRaw('LOWER(symbol) LIKE ?', [$query.'%']);
                $sql->orWhereRaw('LOWER(name) LIKE ?', ['%'.$query.'%']);
            })
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get()
            ->makeHidden(['logo_url', 'title']); // hide custom logo_url and title attributes appended by default

        return [
            'success' => TRUE,
            'results' => $assets
        ];
    }

    public function info(Request $request)
    {
        if (!$request->ids) {
            return abort(404);
        }

        $assetsIds = array_filter($request->ids, function ($id) {
            return intval($id) > 0;
        });

        return Asset::where('status', Asset::STATUS_ACTIVE)->whereIn('id', $assetsIds)->get();
    }

    public function history(Asset $asset, $range)
    {
        $tockMarketHistoricalDataApi = new StockMarketHistoricalDataApi($asset->symbol_ext);
        return $tockMarketHistoricalDataApi->history($range);
    }
}