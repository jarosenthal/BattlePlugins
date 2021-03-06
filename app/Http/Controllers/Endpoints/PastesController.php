<?php

namespace App\Http\Controllers\Endpoints;

use App\API\StatusCodes\ApiStatusCode;
use App\API\Transformers\PasteTransformer;
use App\API\Webhooks;
use App\Models\Paste;
use App\Repositories\PasteRepository;
use App\Tools\Domain;
use App\Tools\UserSettings;
use Auth;
use Illuminate\Http\Request;

/**
 * Class PastesController
 * @package App\Http\Controllers\Endpoints
 */
class PastesController extends ApiController {
    /**
     * @var PasteTransformer
     */
    protected $pasteTransformer, $statusCode, $webhooks, $request;
    /**
     * @var int
     */
    private $limit = 5;

    /**
     * @param PasteTransformer $pasteTransformer
     * @param ApiStatusCode $statusCode
     * @param Webhooks $webhooks
     */
    function __construct(PasteTransformer $pasteTransformer, ApiStatusCode $statusCode, Webhooks $webhooks, Request $request) {
        $this->middleware('auth.api', ['except' => ['show', 'index']]);
        $this->pasteTransformer = $pasteTransformer;
        $this->statusCode = $statusCode;
        $this->webhooks = $webhooks;
        $this->request = $request;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index() {
        $limit = $this->request->input('limit', $this->limit);
        $limit = $limit > $this->limit ? $this->limit : $limit;

        $pastes = Paste::wherePublic(true);

        if (Auth::check())
            $pastes->orWhere('creator', Auth::user()->id);

        $pastes = $pastes->paginate($limit);

        return $this->returnWithPagination($pastes, [
            'data' => $this->pasteTransformer->transformCollection($pastes->all())
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id) {
        $paste = Paste::find($id);

        if (!$paste)
            return $this->statusCode->respondNotFound("Paste does not exist!");
        else if (!$paste->public) {
            if (!(Auth::check() && $paste->user_id == Auth::user()->id))
                return $this->statusCode->respondValidationFailed("You don't have permission to view this paste.");
        }

        return $this->statusCode->respond([
            'data' => $this->pasteTransformer->transform($paste)
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store() {
        if (UserSettings::hasNode(auth()->user(), UserSettings::CREATE_PASTE)) {
            $content = $this->request->input('content');
            $force = $this->request->input('force');

            if (!$content)
                return $this->statusCode->respondWithError("A required field has been left blank.");
            else if (strlen($content) > env("PASTE_MAX_LEN", 500000) && !$force)
                return $this->statusCode->respondWithError("Paste exceeds " . env("PASTE_MAX_LEN", 500000) . " max character limit. Set the force param to true to cut your paste after " . env("PASTE_MAX_LEN", 500000) . "characters");

            $slug = Domain::generateSlug();

            file_put_contents(storage_path() . "/app/pastes/$slug.txt", $content);

            Paste::create([
                'slug'    => $slug,
                'user_id' => auth()->user()->id,
                'title'   => $this->request->input('title') ?: $slug,
                'public'  => $this->request->input('public') ?: false
            ]);

            return $this->statusCode->respondCreated($slug);
        } else
            return $this->statusCode->respondValidationFailed();
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id) {
        $paste = Paste::find($id);
        if ($paste->user_id == Auth::user()->id) {
            PasteRepository::delete($id);
            return $this->statusCode->respondWithSuccess("Paste has been deleted.");
        }

        return $this->statusCode->respondValidationFailed("You don't have permission to delete this paste.");
    }
}