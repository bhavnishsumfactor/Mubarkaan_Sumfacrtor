<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Resource extends Model
{
    use HasFactory;

    protected $primaryKey = 'resource_id';

    const CREATED_AT = 'resources_created_at';
    const UPDATED_AT = 'resources_cat_updated_at';

    public static function getResources($request)
    {
        //return Cache::remember(static::cacheKey(), 30 * 60, function () use ($request) {
            $query = Resource::select(
                'resources.resource_id', 
                'resources.resource_title as title',
                'resources.resource_parent_id'
            );
            if (!empty($request['search'])) {
                $query->where('resource_title', 'like', '%' . $request['search'] . '%')
                       ->orWhere('resource_description', 'like', '%' . $request['search'] . '%');
            }
            return $query->get()->toArray();
        //});
    }
    public static function buildTree($elements, $parentId = 0, $childrenContainer = 'children')
    {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['resource_parent_id'] == $parentId) {
                $children = self::buildTree($elements, $element['resource_id'], $childrenContainer);
                if ($children) { 
                    $element[$childrenContainer] = $children;
                } else {
                    $element['isLeaf'] = true;
                    $element[$childrenContainer] = [];
                }
                $element['isDraggable'] = false;
                $element['isSelectable'] = true;
                $element['isExpanded'] = false;
                $element['data']['id'] = $element['resource_id'];
                unset($element['resource_id']);
                unset($element['resource_parent_id']);
                $branch[] = $element;
            }
        }
        return $branch;
    }
    public static function details($resourceId = 0, $title = '')
    {
        $query = Resource::select('resources.resource_description as description');
        if ($resourceId == 0) {
            $query->where('resource_title', $title);
        } else {
            $query->where('resource_id', $resourceId);
        }
        return $query->first();
    }
    public static function searchRecords($search)
    {
        $query = Resource::select('resource_id', 'resource_title', 'resource_description');
        self::searchProductsByKeywords($query, $search);
        return $query->limit(10)->get();
    }
    public static function searchProductsByKeywords($query, $keyword)
    {
         $fieldConditions = "( case when resource_title LIKE '%" . $keyword . "%' then 10 else 0 end )  +
                ( case when resource_description LIKE '%" . $keyword . "%' then 3 else 0 end )";
        $query->selectRaw($fieldConditions . 'as relevancy');
        $query->where('resource_title', 'like', '%' . $keyword . '%')
        ->orWhere('resource_description', 'like', '%' . $keyword . '%');
        $query->orderBy('relevancy', 'desc');
        return $query;
    }
}
