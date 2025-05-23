<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class PasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        try {
            Log::info('=== PASSWORD CHANGE DEBUG START ===');
            Log::info('Request method: ' . $request->method());
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Headers: ', $request->headers->all());
            Log::info('Request body: ', $request->all());

            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required',
            ]);
            
            Log::info('Validation passed');

            $token = $request->bearerToken();
            Log::info('Bearer token: ' . ($token ? substr($token, 0, 20) . '...' : 'null'));
            
            if (!$token) {
                Log::error('No bearer token found');
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan'
                ], 401);
            }

            $accessToken = PersonalAccessToken::findToken($token);
            Log::info('Access token found: ' . ($accessToken ? 'yes' : 'no'));
            
            if (!$accessToken) {
                Log::error('Token not found in database');
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid'
                ], 401);
            }

            $user = $accessToken->tokenable;
            Log::info('User from token: ', [
                'user_id' => $user ? $user->id : 'null',
                'user_class' => $user ? get_class($user) : 'null',
                'tokenable_type' => $accessToken->tokenable_type,
                'tokenable_id' => $accessToken->tokenable_id
            ]);
            
            if (!$user) {
                Log::error('User not found from token');
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }

            Log::info('Checking current password...');
            if (!Hash::check($request->current_password, $user->password)) {
                Log::warning('Current password verification failed');
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini tidak valid'
                ], 400);
            }
            
            Log::info('Current password verified');

            if (Hash::check($request->new_password, $user->password)) {
                Log::warning('New password same as current');
                return response()->json([
                    'success' => false,
                    'message' => 'Password baru tidak boleh sama dengan password lama'
                ], 400);
            }

            Log::info('Updating password...');
            $user->password = Hash::make($request->new_password);
            $saved = $user->save();
            
            Log::info('Password save result: ' . ($saved ? 'success' : 'failed'));

            if (!$saved) {
                Log::error('Failed to save password');
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan password'
                ], 500);
            }

            Log::info('Password changed successfully');
            Log::info('=== PASSWORD CHANGE DEBUG END ===');

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Exception caught: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}