<?php
/**
 * author     jianglong
 * date       2019/8/27 上午10:22
 */

namespace crusj\php_crumbs\traits;

/**
 * 获取分页接口分页参数
 * Trait PageParams
 * @package php_crumbs\traits
 * @author jianglong
 */
trait PageParams
{
    public function pageParams(string $pageName = 'page', string $pageSizeName = 'page_size', int $defaultPageSize = 10): array
    {
        $currentPage = intval($_GET[$pageName]) ?? 1;
        $currentPage = $currentPage < 1 ? 1 : $currentPage;

        $pageSize = intval($_GET['page_size']) ?? $defaultPageSize;
        $pageSize = $pageSize < 0 ? 10 : $pageSize;
        return [$currentPage,$pageSize];
    }
}
