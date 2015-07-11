<?php

namespace App\Tools\Misc;

use App\Tools\URL\Domain;
use Awjudd\FeedReader\Facades\FeedReader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Jenkins {

    public static function getFeed($endpoint = 'rssAll', $limit = 4, $start = 0) {
        $feed = FeedReader::read(env("JENKINS_URL") . '/' . $endpoint);
        $feed->enable_order_by_date();
        return $feed->get_items($start, $limit);
    }

    public static function getJobs($job = null) {
        if ($job) {
            return Cache::get($job . '_jobs', function () use ($job) {
                $key = $job . '_jobs';
                Cache::forget($key);
                $update = Jenkins::updateJobs($job);
                Cache::forever($key, $update);
                return $update;
            });
        } else {
            return Cache::get('_jobs', function () {
                $key = '_jobs';
                Cache::forget($key);
                $update = Jenkins::updateJobs();
                Cache::forever($key, $update);
                return $update;
            });
        }
    }

    public static function getBuild($job, $build) {
        return Cache::get($job . '_' . $build, function () use ($job, $build) {
            $key = $job . '_' . $build;
            Cache::forget($key);
            $update = static::updateBuild($job, $build);
            Cache::forever($key, $update);
            return $update;
        });
    }

    public static function getStableBuilds($job = null, $limit = null, $start = 0) {
        $stableBuilds = [];

        foreach (static::getAllBuilds($job) as $build) {
            if ($build->result == 'SUCCESS')
                $stableBuilds[] = $build;
        }

        $stableBuilds = array_sort($stableBuilds, function ($value) {
            return -1 * $value->timestamp;
        });

        if ($limit)
            return array_slice($stableBuilds, $start, $limit);

        return $stableBuilds;
    }

    public static function getAllBuilds($job = null, $limit = null, $start = 0) {
        $builds = [];

        if ($job) {
            $job = Jenkins::getJobs($job);

            foreach ($job->builds as $build)
                array_push($builds, Jenkins::getBuild($job->name, $build->number));

        } else {
            foreach (static::getJobs() as $job) {
                $job = static::getJobs($job->name);

                foreach ($job->builds as $build)
                    $builds[] = static::getBuild($job->name, $build->number);
            }
        }

        $builds = array_sort($builds, function ($value) {
            return -1 * $value->timestamp;
        });

        if ($limit)
            return array_slice($builds, $start, $limit);

        return $builds;
    }

    public static function updateJobs($job = null) {
        if ($job) {
            $url = '/job/' . $job . '/api/json';

            $content = file_get_contents(env('JENKINS_URL') . $url);
            $content = json_decode($content);

            return $content;
        } else {
            $url = '/api/json';
            $content = file_get_contents(env('JENKINS_URL') . $url);
            $content = json_decode($content);

            return $content->jobs;
        }
    }

    public static function updateBuild($job, $build) {
        $url = env('JENKINS_URL') . '/job/' . $job . '/' . $build . '/api/json';
        return json_decode(file_get_contents($url));
    }

    public static function getBuildVersion($job, $build) {
        $url = env('JENKINS_URL') . '/job/' . $job . '/' . $build . '/artifact/pom.xml';
        if (Domain::remoteFileExists($url)) {
            $content = new \SimpleXMLElement(file_get_contents($url));

            return 'v' . $content->version;
        } else
            return '#' . static::getBuild($job, $build)->number;
    }

}