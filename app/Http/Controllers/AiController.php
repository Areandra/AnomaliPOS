<?php

namespace App\Http\Controllers;

use App\Ai\Agents\JarvisAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    /**
     * Mengirim data konteks aplikasi ke AI Core Engine.
     */
    public function processEngine(Request $request)
    {
        $request->validate([
            'context_module' => 'required|in:dashboard,cashier,kitchen,customer,shift',
            'payload' => 'required|array'
        ]);

        $module = $request->input('context_module');
        $payloadData = $request->input('payload');

        // 1. Definisikan Master Prompt sebagai System Prompt
        // 2. Gabungkan modul aktif dan payload data sebagai pesan user
        $userMessage = "Mulai proses analisis dengan menggunakan aturan Mode: [" . $module . "] yang ada di interaction. Berikut adalah data payload JSON mentah dari database aplikasi: " . json_encode($payloadData);


        $respone = JarvisAgent::make()->prompt($userMessage);
        return response()->json(json_decode($respone, true));
    }
}
