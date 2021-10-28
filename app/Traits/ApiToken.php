<?php

namespace App\Traits;

use Laravel\Sanctum\PersonalAccessToken;

trait ApiToken
{

	protected function findToken($request)
	{
        $requestToken = $request->header('authorization');
        $personalAccessToken = new PersonalAccessToken();
        $token = $personalAccessToken->findToken(str_replace('Bearer', '', $requestToken));
		return $token;
	}


}