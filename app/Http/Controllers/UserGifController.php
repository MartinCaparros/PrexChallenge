<?php

namespace App\Http\Controllers;

use App\Http\Requests\GifSearchRequest;
use App\Http\Requests\GifSearchByIdRequest;
use App\Http\Requests\StoreFavoriteGifRequest;

use App\Http\Resources\UserFavoriteGifStoreResource;

use App\Interfaces\UserGifRepositoryInterface;

use App\Services\GIPHYService;

use Illuminate\Routing\Controller;

class UserGifController extends Controller
{

    /** @var UserGifRepositoryInterface $userRepository */
    protected $userGifRepository = null;

    public function __construct(UserGifRepositoryInterface $userGifRepository)
    {
        $this->userGifRepository = $userGifRepository;
    }

    /**
     * Search GIF by query
     *
     * @param GifSearchByIdRequest $request
     * @return mixed
     **/
    public function search(GifSearchRequest $request) {

        $result = GIPHYService::searchGif($request->search, $request->limit, $request->offset);

        return response()->json($result);
    }

    /**
     * Search GIF by id
     *
     * @param GifSearchByIdRequest $request
     * @return mixed
     **/
    public function searchById(GifSearchByIdRequest $request)
    {
        $result = GIPHYService::searchById($request->id);

        return response()->json($result);
    }

    /**
     * Store new favorite gif
     *
     * @param StoreFavoriteGifRequest $request
     * @return mixed
     **/
    public function storeFavorite(StoreFavoriteGifRequest $request)
    {
        $new_favorite = $this->userGifRepository->store($request->only('user_id', 'gif_id', 'alias'));

        return response(new UserFavoriteGifStoreResource($new_favorite), 200);
    }
}
