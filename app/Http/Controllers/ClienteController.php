<?php

namespace App\Http\Controllers;

// use App\Cliente; // Modelo do Cliente
use App\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{

    public function updateName(Request $request)
    {

        // $validatedData = $request->validate([
        //     'nome' => 'required|string|max:255',
        // ]);

        // dd('teste');
        // $cliente = Cliente::find($request->id);
        // dd($request['nome']);



        // $cliente->nome = $request->nome;
        // $cliente->save();
        // return response()->json($cliente);

        try {
            // Buscar cliente pelo ID
            $cliente = Cliente::findOrFail($request->id);

            // Validação dos dados
            $validatedData = $request->validate([
                'nome' => 'sometimes|required|string|max:255',
                'telefone' => 'sometimes|required|string|max:15|regex:/^\+?[0-9\s\-]+$/',
                'cpf' => 'sometimes|required|string|size:11|regex:/^\d{11}$/|unique:clientes,cpf,' . $cliente->id,
                'placa' => 'sometimes|required|string|size:7|regex:/^[A-Za-z]{3}[0-9][A-Za-z0-9][0-9]{2}$/',
            ]);

            // Atualizar o cliente
            $cliente->update($validatedData);

            // Retornar resposta de sucesso
            return response()->json([
                'message' => 'Cliente atualizado com sucesso!',
                'cliente' => $cliente,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Retornar erro se o cliente não for encontrado
            return response()->json([
                'message' => 'Cliente não encontrado.',
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Retornar erros de validação
            return response()->json([
                'message' => 'Erro de validação.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Retornar erro genérico
            return response()->json([
                'message' => 'Erro ao atualizar o cliente.',
                'error' => $e->getMessage(),
            ], 500);
        }
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
    
    public function getClient(Request $request)
    {

        $cliente = Cliente::find($request->id);
        if(!$cliente){
            return response()->json([
                'error' => 'Não encontramos nenhum Cliente.'
            ], 500);
        }
     
        return response()->json($cliente);
    }


    
    public function consultarPorUltimoNumeroPlaca($numero)
    {
            // Validação para garantir que o número é válido
        if (!is_numeric($numero) || strlen($numero) != 1) {
            return response()->json(['error' => 'Número inválido. Informe um número entre 0 e 9.'], 400);
        }

        // Buscar os clientes onde o último número da placa é igual ao informado
        $clientes = Cliente::whereRaw('RIGHT(placa, 1) = ?', [$numero])->get();

        // Verificar se há resultados
        if ($clientes->isEmpty()) {
            return response()->json(['message' => 'Nenhum cliente encontrado com este critério.'], 404);
        }

        // Retornar os dados dos clientes
        return response()->json($clientes, 200);
    }

    public function delete(Request $request)
    {
        $cliente = Cliente::find($request->id);
        if(!$cliente){
            return response()->json(['error' => 'não encontrei o cliente'], 400);
        }
        $cliente->delete();
        return response()->json($cliente);
    }


}