<?php

use Illuminate\Support\Facades\Http;

function getUser($userId) {
    $url = env('URL_USER_SERVICE'). 'users/'. $userId;
    
    try {
        $response = Http::timeout(10)->get($url);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return response()->json([
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ]);
    }
}

function getUserByIds($userIds = []) {
    $url = env('URL_USER_SERVICE'). 'users/';
    
    try {
        if (count($userIds) === 0) {
            return response()->json([
                'status' => 'success',
                'http_code' => 200,
                'data' => []
            ]);
        }
        
        $response = Http::timeout(10)->get($url, ['user_ids[]' => $userIds]);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service user unavailable'
        ];
    };
}

function postOrder($params) {
    $url = env('URL_ORDER_SERVICE') . 'api/orders';
    try {
        $response = Http::post($url, $params);
        $data = $response->json();
        $data['http_code'] = $response->getStatusCode();
        return $data;
    } catch (\Throwable $th) {
        return [
            'status' => 'error',
            'http_code' => 500,
            'message' => 'service order unavailable'
        ];
    }
}