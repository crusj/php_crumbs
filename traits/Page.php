<?php
/**
 * author crusj
 * date   2019/10/12 11:41 上午
 */


namespace crusj\php_crumbs\traits;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;

trait Page
{
    /**
     * 分页参数
     * @return array
     */
    public function pageParam(): array
    {
        $all = request()->all();
        return [
            'per_page' => isset($all['per_page']) ? intval($all['per_page']) : 10,
            'page'     => isset($all['page']) ? (intval($all['page']) > 0 ? intval($all['page']) : 1) : 1,
        ];
    }

    /**
     * @param LengthAwarePaginator $pages
     * @param array $fields
     * @param array $extra 额外参数
     * @return array
     */
    public function parsePage(LengthAwarePaginator $pages, array $fields = ['*'], array $extra = []): array
    {
        //字段别名
        $alias = [];
        //真实字段
        $originalFields = [];
        if ($fields[0] != '*') {
            $alias = collect($fields)->mapWithKeys(function ($item) use (&$originalFields) {
                $ex = explode(' ', $item);
                $originalFields[] = $ex[0];
                if (count($ex) == 2) {
                    return [$ex[0] => $ex[1]];
                }
                return [];
            })->toArray();
        }
        return [
            'count'        => $pages->total(),
            'current_page' => $pages->currentPage(),
            'total_page'   => ceil($pages->total() / $pages->perPage()),
            'per_page'     => $pages->perPage(),
            'data'         => collect($pages->items())->map(function ($item) use ($fields, $originalFields, $alias) {
                if ($fields[0] == '*') {
                    return true;
                } else {
                    if (!is_array($item)) {
                        if ($item instanceof Arrayable) {
                            $item = $item->toArray();
                        } else {
                            $item = [];
                        }
                    }

                    //过滤不存在的字段
                    $filterArr = array_filter($item, function ($key) use ($originalFields, $alias) {
                        return in_array($key, $originalFields);
                    }, ARRAY_FILTER_USE_KEY);

                    //字段别名
                    foreach ($filterArr as $key => $value) {
                        if (isset($alias[$key])) {
                            unset($filterArr[$key]);
                            $filterArr[$alias[$key]] = $value;
                        }
                    }
                    return $filterArr;
                }
            })->toArray(),
            'extra'        => $extra
        ];
    }
}
