<?php
/**
 * author     jianglong
 * date       2019/8/27 上午10:14
 */

namespace php_crumbs\classes;

class ParseDate
{
    //将月份按照周天数分段,返回某年某月的周段内日期
    private function month2Week(int $year, int $month): array
    {
        $start = 1;
        $end = date('d', strtotime(date("{$year}-{$month}-01") . ' + 1 month - 1 day'));
        $days = range($start, $end);
        return array_chunk($days, 7);
    }

    //时间戳判断当前是第几周
    private function whichWeek(int $timeStamp): int
    {
        $date = date('Y-m-d', $timeStamp);
        list($year, $month, $day) = explode('-', $date);
        $weeks = $this->month2Week($year, $month);
        foreach ($weeks as $key => $weekDays) {
            if (in_array($day, $weekDays)) {
                return $key + 1;
            }
        }
        return 0;
    }
}

