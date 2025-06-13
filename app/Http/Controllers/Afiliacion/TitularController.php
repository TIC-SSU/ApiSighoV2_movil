<?php

namespace App\Http\Controllers\Afiliacion;

use App\Http\Controllers\Controller;
use App\Services\Afiliacion\TitularService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TitularController extends Controller
{
    //
    protected $titularService;

    public function __construct(TitularService $titularService)
    {
        $this->titularService = $titularService;
    }

    public function imgan_titular(Request $request, $id_titular)
    {
        try {
            return $this->titularService->imagen_titular($id_titular);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Error de validación ',
                'errors' => $e->errors(),
            ], 422);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'status' => $e->getStatusCode() . ' ' . 'imagen_titular',
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (HttpException $e) {
            return response()->json([
                'status' => $e->getStatusCode(),
                'success' => false,
                'message' => $e->getMessage() . ' ' . 'imagen_titular',
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Error en titularService' . ' ' . 'imagen_titular',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
}
