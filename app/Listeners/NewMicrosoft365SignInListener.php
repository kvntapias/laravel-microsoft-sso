<?php

namespace App\Listeners;

use App\Models\User;
// use Dcblogdev\MsGraph\MsGraph;
use Illuminate\Support\Facades\Auth;

use Dcblogdev\MsGraph\Models\MsGraphToken;

class NewMicrosoft365SignInListener
{
    public function handle($event)
    {
        if (!config('app.sso_enabled')) {
            abort(500, "Módulo No disponible");
        }

        $tokenId = $event->token['token_id'];
        $token   = MsGraphToken::find($tokenId)->first();

        // Buscar un usuario con el ms_object_id proporcionado por Microsoft
        $user = User::where('ms_object_id', $event->token['info']['id'])->first();

        if ($user) {
            if (!$user->status) {
                abort(403, "Su usuario está bloqueado temporalmente. Por favor póngase en contacto con el administrador");
            }
            Auth::login($user);
        } 

        else {
            // Verificar si el correo electrónico ya está registrado
            $existingUser = User::where('email', $event->token['info']['mail'])->first();

            if ($existingUser) {
                if (!$existingUser->status) {
                    abort(403, "Su usuario está bloqueado temporalmente. Por favor póngase en contacto con el administrador");
                }
                $existingUser->timestamps = false;
                // Si el correo electrónico ya está registrado, actualizar la información y agregar el ms_object_id
                $existingUser->update([
                    'name'     => $event->token['info']['givenName'],
                    'surname'     => $event->token['info']['surname'],
                    'ms_object_id' => $event->token['info']['id'],
                ]);

                Auth::login($existingUser);
            } else {
                // Si el correo electrónico no está registrado, crear un nuevo usuario
                $user = User::create([
                    'name'     => $event->token['info']['givenName'],
                    'surname'     => $event->token['info']['surname'],
                    'email'    => $event->token['info']['mail'],
                    'ms_object_id' => $event->token['info']['id'],
                    'password' => '',
                ]);

                $token->user_id = $user->id;
                $token->save();

                Auth::login($user);
            }
        }

        Auth::login($user);
    }
}