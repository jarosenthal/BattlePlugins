<?php namespace App\Http\Controllers\Download;

use App\Http\Controllers\Controller;
use App\Tools\Misc\Jenkins;
use App\Tools\Models\ProductionBuilds;
use Auth;

class PageController extends Controller {

    public function index($current_job = null) {
        $jobs = Jenkins::getJobs();

        $curr = null;
        if($current_job)
            $curr = Jenkins::getJobs($current_job);

        return view('download.index', [
            'jobs' => $jobs,
            'rssFeed' => Jenkins::getFeed('rssLatest'),
            'current_job' => $curr,
            'stableBuilds' => Jenkins::getStableBuilds($current_job, 20),
            'production' => new ProductionBuilds()
        ]);
    }

    public function toggleProduction($job) {
        $jobid = ProductionBuilds::find($job);
        if($jobid)
            $jobid->delete();
        else
            ProductionBuilds::create(['id'=>$job]);

        return redirect()->back();
    }

}