<?php
namespace App\Tools;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Psr\Log\LogLevel;
use ReflectionClass;

/**
 * Class LaravelLogViewer
 * @package Rap2hpoutre\LaravelLogViewer
 */
class LaravelLogViewer {

    /**
     * @var string file
     */
    private static $file;

    /**
     * @var array
     */
    private static $levels_classes = [
        'debug' => 'blue',
        'info' => 'blue',
        'notice' => 'green',
        'warning' => 'yellow',
        'error' => 'red',
        'critical' => 'red',
        'alert' => 'red',
    ];

    /**
     * @return string
     */
    public static function getFileName() {
        return basename(self::$file);
    }

    /**
     * @param null $l
     * @param int $curPage
     * @param int $perPage
     * @param null $display_level
     * @return LengthAwarePaginator
     */
    public static function getPaginated($l = null, $curPage = 1, $perPage = 15, $display_level = null) {
        if ($l)
            static::setFile(base64_decode($l));

        $logs = collect(static::all($display_level));
        return new LengthAwarePaginator($logs->forPage($curPage, $perPage), $logs->count(), $perPage, $curPage);
    }

    /**
     * @param string $file
     */
    public static function setFile($file) {
        if (File::exists(storage_path() . '/logs/' . $file)) {
            self::$file = storage_path() . '/logs/' . $file;
        }
    }

    /**
     * @param $display_level
     * @return array
     */
    public static function all($display_level = null) {
        $log = array();

        $log_levels = self::getLogLevels();

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/';

        if (!self::$file) {
            $log_file = self::getFiles();
            if (!count($log_file)) {
                return [];
            }
            self::$file = $log_file[0];
        }

        $file = File::get(self::$file);

        preg_match_all($pattern, $file, $headings);

        if (!is_array($headings)) return $log;

        $log_data = preg_split($pattern, $file);

        if ($log_data[0] < 1) {
            array_shift($log_data);
        }

        foreach ($headings as $h) {
            for ($i = 0, $j = count($h); $i < $j; $i++) {
                foreach ($log_levels as $level_key => $level_value) {
                    if (strpos(strtolower($h[$i]), '.' . $level_value)) {

                        preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?\.' . $level_key . ': (.*?)( in .*?:[0-9]+)?$/', $h[$i], $current);

                        if (!isset($current[2])) continue;

                        if (!$display_level || $display_level == $level_value) {
                            $log[] = array(
                                'level' => $level_value,
                                'level_class' => self::$levels_classes[$level_value],
                                'date' => $current[1],
                                'text' => $current[2],
                                'in_file' => isset($current[3]) ? $current[3] : null,
                                'stack' => preg_replace("/^\n*/", '', $log_data[$i])
                            );
                        }
                    }
                }
            }
        }

        return array_reverse($log);
    }

    /**
     * @return array
     */
    private static function getLogLevels() {
        $class = new ReflectionClass(new LogLevel);
        return $class->getConstants();
    }

    /**
     * @param bool $basename
     * @return array
     */
    public static function getFiles($basename = false) {
        $files = glob(storage_path() . '/logs/*');
        $files = array_reverse($files);
        if ($basename && is_array($files)) {
            foreach ($files as $k => $file) {
                $files[$k] = basename($file);
            }
        }
        return $files;
    }

    /**
     * @return array
     */
    public static function getLogLevelsClasses() {
        return static::$levels_classes;
    }
}
