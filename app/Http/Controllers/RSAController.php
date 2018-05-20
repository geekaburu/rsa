<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class RSAController extends Controller
{
    public function encrypt(Request $request)
    {
    	$path = public_path('storage/py/encryption.py');
		$result = shell_exec('python '.$path.' '.escapeshellarg($request->text));
        $json = json_decode($result);

        // Return response
        return response([
            'encrypted' => $json->{'encrypted'},
            'd' => $json->{'d'},
            'n' => $json->{'n'},
            'e' => $json->{'e'},
        ], 200);
    }

    public function decrypt(Request $request)
    {
    	$path = public_path('storage/py/decryption.py');
        $result = shell_exec('python '.$path.' '.$request->text .' '.$request->d .' '.$request->n);
		// Return response
        return response([
            'decrypted' => json_decode($result)->{'text'},
        ], 200);
    }
}
