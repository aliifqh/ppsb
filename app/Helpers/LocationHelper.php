<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class LocationHelper
{
    public static function getProvinceName($id)
    {
        try {
            if (empty($id)) return '';

            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/province/{$id}.json");
            if ($response->successful() && $response->body()) {
                $data = $response->json();
                return $data['name'] ?? $id;
            }
            return $id;
        } catch (\Exception $e) {
            \Log::error('Error getting province name: ' . $e->getMessage());
            return $id;
        }
    }

    public static function getRegencyName($id)
    {
        try {
            if (empty($id)) return '';

            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regency/{$id}.json");
            if ($response->successful() && $response->body()) {
                $data = $response->json();
                return $data['name'] ?? $id;
            }
            return $id;
        } catch (\Exception $e) {
            \Log::error('Error getting regency name: ' . $e->getMessage());
            return $id;
        }
    }

    public static function getDistrictName($id)
    {
        try {
            if (empty($id)) return '';

            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/district/{$id}.json");
            if ($response->successful() && $response->body()) {
                $data = $response->json();
                return $data['name'] ?? $id;
            }
            return $id;
        } catch (\Exception $e) {
            \Log::error('Error getting district name: ' . $e->getMessage());
            return $id;
        }
    }

    public static function getVillageName($id)
    {
        try {
            if (empty($id)) return '';

            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/village/{$id}.json");
            if ($response->successful() && $response->body()) {
                $data = $response->json();
                return $data['name'] ?? $id;
            }
            return $id;
        } catch (\Exception $e) {
            \Log::error('Error getting village name: ' . $e->getMessage());
            return $id;
        }
    }
}
