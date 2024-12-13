<?php

namespace App\Http\Controllers;

// use App\Cliente; // Modelo do Cliente
use App\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function updateName(Request $request)
    {

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $cliente = Cliente::find($id);
        $cliente->nome = $request->nome;
        $cliente->update();
        return response()->json($cliente);
    }


    public function add(Request $request)
    {
        try {
            // Validação dos dados com mensagens personalizadas
            $validatedData = $request->validate([
                'nome' => 'required|string|max:255',
                'telefone' => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^\+?[0-9\s\-]+$/', // Aceita números, espaço, hífen, e código de país
                ],
                'cpf' => [
                    'required',
                    'string',
                    'size:11',
                    'unique:clientes,cpf',
                    'regex:/^\d{11}$/', // Apenas números, sem caracteres especiais
                ],
                'placa' => [
                    'required',
                    'string',
                    'size:7',
                    'regex:/^[A-Za-z]{3}[0-9][A-Za-z0-9][0-9]{2}$/',
                ],
            ], [
                // Mensagens personalizadas
                'nome.required' => 'O nome é obrigatório.',
                'nome.max' => 'O nome deve ter no máximo 255 caracteres.',
                'telefone.required' => 'O telefone é obrigatório.',
                'telefone.regex' => 'O telefone deve conter apenas números, espaços, ou hífens.',
                'cpf.required' => 'O CPF é obrigatório.',
                'cpf.size' => 'O CPF deve ter exatamente 11 dígitos.',
                'cpf.unique' => 'O CPF já está cadastrado.',
                'cpf.regex' => 'O CPF deve conter apenas números.',
                'placa.required' => 'A placa do carro é obrigatória.',
                'placa.size' => 'A placa deve ter exatamente 7 caracteres.',
                'placa.regex' => 'A placa deve estar no formato válido, como ABC1234.',
            ]);
    
            // Criar o cliente no banco de dados
            $cliente = Cliente::create($validatedData);
    
            // Retornar a resposta de sucesso
            return response()->json([
                'message' => 'Cliente cadastrado com sucesso!',
                'cliente' => $cliente,
            ], 201); // Código HTTP 201 para recurso criado com sucesso
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura erros de validação e retorna resposta detalhada
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $e->errors(),
            ], 422); // Código HTTP 422 para erro de validação
    
        } catch (\Exception $e) {
            // Captura outros erros e retorna uma resposta genérica
            return response()->json([
                'message' => 'Ocorreu um erro inesperado ao cadastrar o cliente.',
                'error' => $e->getMessage(),
            ], 500); // Código HTTP 500 para erro no servidor
        }
    }
    
    
    
    public function consultarPorUltimoNumeroPlaca($numero)
    {
        // Validação para garantir que o número é válido
        if (!is_numeric($numero) || strlen($numero) != 1) {
            return response()->json(['error' => 'Número inválido. Informe um número entre 0 e 9.'], 400);
        }

        // Buscar os clientes onde o último número da placa é igual ao informado
        $clientes = Cliente::whereHas('carros', function ($query) use ($numero) {
            $query->whereRaw('RIGHT(placa, 1) = ?', [$numero]);
        })->get();

        // Verificar se há resultados
        if ($clientes->isEmpty()) {
            return response()->json(['message' => 'Nenhum cliente encontrado com este critério.'], 404);
        }

        // Retornar os dados dos clientes
        return response()->json($clientes, 200);
    }
}
