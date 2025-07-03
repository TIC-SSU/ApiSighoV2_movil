<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\Administracion\PersonaService;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $personaService;
    protected $userService;

    public function __construct(PersonaService $personaService, UserService $userService)
    {
        $this->personaService = $personaService;
        $this->userService = $userService;
    }
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
            // return response()->json(compact('token'));
            // dd("entrando a buscar al usuario");
            $user = User::where('email', $request->email)
                ->with(['personaUser.tipoAseguradoPersona'])
                ->firstOrFail();
            // return $user->personaUser->afiliado;
            if (!$user->personaUser->afiliado) {
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'El usuario ' . $user->name .  ' no esta afiliado',

                ], 403);
            }
            // dd($user);
            $request->merge(['id_persona' => $user->id_persona]);
            $id_titular = $this->personaService->obtener_id_titular_login($user->id_persona);
            $user->id_persona_titular = $id_titular;
            $user->imagen_usuario = $this->userService->url_imagen($user->id);
            // $request->merge(['id_persona_titular' => $id_titular]);

            // $agenda = AgendaController::obtener_agenda_con_idPersona($request);
            // if ($agenda->getData()->status != 200) {
            //     $agenda = null;
            // } else {
            //     $agenda = $agenda->getData()->data;
            // }
            // return 'saliendo de agrenda';
            // return AgendaController::obtener_agenda_con_idPersona($user->id_persona);
            // $fotoBase64 = FotoBase64::buscarImagen($user->id_persona);
            // $fotoUsuarioBase64 = FotoBase64::buscarImagenUsuario($user->id_persona);
            // return $nombre_foto;
            // $token = $user->createToken('auth_token')->plainTextToken;
            // dd($fotoBase64, $fotoUsuarioBase64, $agenda);
            return response()->json([
                'message' => 'Bienvenido ' . $user->name,
                'status' => 200,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                // 'agenda' => $agenda,
                // 'fotoPersonaBase64' => $fotoBase64,
                // 'fotoUsuarioBase64' => $fotoUsuarioBase64,
            ]);
        } catch (\Throwable $th) {
            $dataError = [
                'success' => false,
                'status' => 500,
                'message' => 'Error al Iniciar sesion',
                'error' => $th->getMessage(), // Mensaje del error
                'line' => $th->getLine(), // Línea donde ocurrió el error
                'file' => $th->getFile(), // Archivo donde ocurrió el error
            ];
            return response()->json($this->cleanUtf8($dataError['error']), 500);
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
    private function cleanUtf8($string)
    {
        if (is_string($string)) {
            return mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        }
        return $string;
    }
}
