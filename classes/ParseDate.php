<?php
/**
 * author     jianglong
 * date       2019/8/27 上午10:14
 */

namespace crusj\php_crumbs\classes;

use Carbon\Carbon;

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

    /**
     * 根据时间戳判断当前是第几周
     * @param int $timeStamp 时间戳
     * @return int
     */
    public function whichWeek(int $timeStamp): int
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

    /**
     * 日期转换为几分钟几秒或几周前
     * @param $value 时间戳或时间格式字符串
     * @return string
     */
    public function dateToStr($value): string
    {
        if (is_numeric($value)) {
            $time = $value;
        } else {
            $time = strtotime($value);
        }
        $interval = time() - $time;
        $yearSecond = 365 * 24 * 60 * 60;
        $month = 30 * 24 * 60 * 60;
        $day = 24 * 60 * 60;
        $hour = 60 * 60;
        $minute = 60;
        if (($tmp = $interval / $yearSecond) >= 1) {
            return (int)$tmp . "年前";
        }
        if (($tmp = $interval / $month) >= 1) {
            return (int)$tmp . "月前";
        }
        if (($tmp = $interval / $day) >= 1) {
            return (int)$tmp . "天前";
        }
        if (($tmp = $interval / $hour) >= 1) {
            return (int)$tmp . "小时前";
        }
        if (($tmp = $interval / $minute) >= 1) {
            return (int)$tmp . "分钟前";
        }
        if ($tmp < 1) {
            return "现在";
        } else {
            return $tmp . '秒前';
        }
    }

    /**
     * 时间解析为2019-12-18 (下午/上午) 9点
     * @param string $timeString 时间字符串
     * @return string
     */
    public function parseDateMA(string $timeString): string
    {
        try {
            $time = Carbon::createFromTimeString($timeString);
        } catch (\Exception $e) {
            return "";
        }
        if (in_array($time->hour, range(0, 12))) {
            $ret = $time->toDateString() . ' 上午' . ($time->hour) . '点';
        } else {
            $ret = $time->toDateString() . ' 下午' . ($time->hour) . '点';
        }
        return $ret;
    }

}

