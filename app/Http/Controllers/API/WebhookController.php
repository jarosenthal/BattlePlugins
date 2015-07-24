<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use Auth;
use Illuminate\Http\Request;

/**
 * Class WebhookController
 * @package App\Http\Controllers
 */
class WebhookController extends Controller {

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request) {
        $url = $request->input('url');
        $event = $request->input('event');

        if (!$event || $event == -1)
            return redirect()->back();

        $uid = Auth::user()->id;

        if (!$url)
            auth()->user()->webhooks()->whereEvent($event)->delete();
        else {
            Webhook::updateOrCreate([
                'event' => $event,
                'user_id' => $uid
            ])->update([
                'url' => $url
            ]);
        }

        return redirect()->back();
    }

}