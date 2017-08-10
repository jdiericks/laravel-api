<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use JWTAuth;
use App\Note;
use Dingo\Api\Routing\Helpers;

class NoteController extends Controller
{
    use Helpers;

    public function index()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        return $currentUser
            ->notes()
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();
    }

    public function store(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $note = new Note;

        $note->title = $request->get('title');
        $note->description = $request->get('description');

        if($currentUser->notes()->save($note))
            return $this->response->created();
        else
            return $this->response->error('could_not_create_note', 500);
    }

}

