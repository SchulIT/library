<?php

namespace App\Http;

use Symfony\Component\HttpFoundation\Request;

class HttpUtils {

    /**
     * @param Request $request
     * @param string $paramName
     * @param string $separator (defaults to comma)
     * @return int[]
     */
    public function parseCharacterSeparatedRequestParamAsIntArray(Request $request, string $paramName, string $separator = ','): array {
        $idsAsString = $request->query->get($paramName, '');
        $ids = [ ];

        if(!empty($idsAsString)) {
            $ids = explode(',', $idsAsString);
        }

        return array_map(fn($id) => intval($id), $ids);
    }
}