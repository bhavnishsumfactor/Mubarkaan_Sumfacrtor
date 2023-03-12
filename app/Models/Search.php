<?php

namespace App\Models;

use App\Models\YokartModel;
use Carbon\Carbon;

class Search extends YokartModel
{
    public $timestamps = false;
    protected $primaryKey = 'search_id';
    protected $fillable = ['search_id', 'search_term', 'search_is_found','search_agent_ip','search_created_at', 'search_from', 'search_by'];

    public const SEARCH_FROM_WEB = 1;
    public const SEARCH_FROM_APP = 2;

    public const SEARCH_FROM = [
        'web' => self::SEARCH_FROM_WEB,
        'app' => self::SEARCH_FROM_APP
    ];

    public static function saveSearchTerm($term, $recordCount, $agentIp, $from = "", $by = 0)
    {
        Search::create([
            'search_term' => $term,
            'search_is_found' => $recordCount >= 1 ? 1 : 0,
            'search_agent_ip' => $agentIp,
            'search_created_at' => Carbon::now(),
            'search_from' => !empty($from) ? $from : self::SEARCH_FROM_WEB,
            'search_by' => $by
        ]);
    }

    public static function getRecentSearches($by)
    {
        return Search::select('search_term')->where('search_by', $by)->latest('search_created_at')->limit(10)->get();
    }
}
