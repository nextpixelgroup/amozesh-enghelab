<?php
if (!function_exists('enumNames')) {
    function enumNames($enumCase)
    {
        return collect($enumCase)->pluck('name')->toArray();
    }
}

if (!function_exists('enumFormated')) {
    function enumFormated($enumCase)
    {
        return collect($enumCase)->map(function ($item) {
            return [
                'label' => $item->value,
                'value' => $item->name,
            ];
        });
    }
}

if (!function_exists('sendJson')) {
    function sendJson($status = 'success', $message = '', $data = null)
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($result)->header('Content-type', 'application/json');
    }
}
