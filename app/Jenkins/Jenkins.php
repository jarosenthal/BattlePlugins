<?php

namespace App\Jenkins;

use Awjudd\FeedReader\Facades\FeedReader;

/**
 * Class Jenkins
 * @package App\Jenkins
 */
class Jenkins {

    /**
     * @param string $endpoint
     * @param int $limit
     * @param int $start
     * @return mixed
     */
    public static function getFeed($endpoint = 'rssAll', $limit = 4, $start = 0) {
        $feed = FeedReader::read(env("JENKINS_URL") . '/' . $endpoint);
        $feed->enable_order_by_date();
        return $feed->get_items($start, $limit);
    }

    /**
     * @param null $job
     * @return JenkinsJob|array
     */
    public static function getJobs($job = null) {
        if ($job)
            return new JenkinsJob($job);

        $path = storage_path() . "/jenkins/jobs.json";
        if (!file_exists($path))
            return [];

        $jobs = [];
        foreach (json_decode(file_get_contents($path)) as $job)
            $jobs[] = new JenkinsJob($job->name);

        return $jobs;
    }

    /**
     * @param $build
     * @return JenkinsJob
     */
    public static function getJobFromBuild($build) {
        if ($build instanceof JenkinsBuild)
            return $build->getJob();

        return explode(' ', $build->getData()->fullDisplayName)[0];
    }

    /**
     * @param null $limit
     * @param int $start
     * @return array
     */
    public static function getAllBuilds($limit = null, $start = 0) {
        $builds = [];

        foreach (static::getJobs() as $job)
            $builds = array_merge($builds, $job->getAllBuilds());

        if ($limit)
            return array_slice($builds, $start, $limit);

        return $builds;
    }

    /**
     * @param null $limit
     * @param int $start
     * @return array
     */
    public static function getStableBuilds($limit = null, $start = 0) {
        $builds = [];

        foreach (static::getAllBuilds() as $build) {
            if ($build->getData()->result == 'SUCCESS')
                $builds[] = $build;
        }

        if ($limit)
            return array_slice($builds, $start, $limit);

        return $builds;
    }

    /**
     * @return int
     */
    public static function getBuildDownloadCount() {
        $count = 0;
        foreach (static::getAllBuilds() as $build)
            $count += $build->getDownloadCount();

        return $count;
    }
}