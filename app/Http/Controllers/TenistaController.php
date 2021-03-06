<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;

class TenistaController extends Controller
{
    //

    
    public function login()
    {
        return view('auth.login-tenista');
    }

    public function postLogin(Request $request)
    {    
        
        

        $validator = validator($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);
        //dd($validator->errors());
        if($validator->fails()){
            //  dd($validator->errors());
            return redirect('/tenista/login')->withErrors($validator)->withInput();

        }

        $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];
        //dd($credentials);
//        dd(Auth::guard('tenista')->attempt($credentials));
        if(auth()->guard('tenista')->attempt($credentials)){
  //          dd($credentials);, 'statustenista_id' => 1
            $credentials = array_add($credentials, 'statustenista_id', 1);
            if(auth()->guard('tenista')->attempt($credentials)){
                return view('tenista.index');    
            }
            else{
                Auth::guard('tenista')->logout();
                return redirect('/tenista/login')->withErrors(['email' => 'Desculpe, mas esta conta está desativada! Entre em contato com um administrador'])->withInput();    
            }

        } else {
            //dd($credentials);
           
            $result = app('App\Http\Controllers\Auth\AuthController')->login($request);
            if(!Auth::check()){
//                  if(auth()->guard('tenista')->)
                return redirect('/tenista/login')->withErrors(['email' => 'Login ou senha inválidos!'])->withInput();    
            }else {
                return redirect('/home');
            }
        }

        
    }

    public function logout()
    {
        auth()->guard('tenista')->logout();
        return redirect('/tenista/login');
    }

    public function trocaStatus(Request $request, $id){
        $tenista = \App\Tenista::find($id);
        $tenista->statustenista()->associate(\App\Statustenista::find($request->input('statustenista_id')));
        $tenista->update();
        //return redirect()->route('tenista.detalhe', compact('tenista')); 
        return redirect('/tenista/logout');
    }

    public function trocaStatusPorAdm(Request $request, $id){
        $tenista = \App\Tenista::find($id);
        $tenista->statustenista()->associate(\App\Statustenista::find($request->input('statustenista_id')));
        $tenista->update();
        return redirect()->route('tenista.lista'); 
    }

    public function index()
    {   
        
        return view('tenista.index');
    }


    
    //
    public function adicionar()
    {	
    	
    	return view('tenista.adicionar');
    }




    public function atualizar(Request $request, $id)
    {   

        $this->validate($request, [
            
            'nome' => 'required',
            'datadenascimento' => 'required|date_format:j/m/Y',
            
            'telefone' => 'required|numeric',
            'cidade_id' => 'required',
            'academia' => 'required',
            'sexo' => 'required'
            
        ]);
        

       

        $tenista = \App\Tenista::find($id);
        $tenista->nome = $request->input('nome');
        
        
        $tenista->telefone = $request->input('telefone');
        $cidade = \App\Cidade::find($request->input('cidade_id'));
        $tenista->cidade()->associate($cidade);
              
        
        $tenista->sexo = $request->input('sexo');
        
        

        //\App\Cliente::find($id)->addTenista($tenista);
        $tenista->update();

        
        \Session::flash('flash_message',[
            'msg'=>"Tenista atualizado com Sucesso!",
            'class'=>"alert-success"
        ]);

        return view('tenista.index');
        
    }

        public function salvar(Request $request)
    {   
        //dd($request);
        $this->validate($request, [
            
            'nome' => 'required',
            'login' => 'required|unique:tenistas',
            'password' => 'required|min:8|max:16',
            'datadenascimento' => 'required|date_format:j/m/Y',
            'email' => 'required|email|unique:tenistas',
            'telefone' => 'required|numeric',
            'cidade_id' => 'required',
            'academia_id' => 'required',
            'sexo' => 'required'
            
        ]);

        $tenista = new \App\Tenista;
        $tenista->nome = $request->input('nome');
        $tenista->login = $request->input('login');
        $tenista->password = bcrypt($request->input('password'));
        $tenista->email = $request->input('email');
        $tenista->telefone = $request->input('telefone');
        $cidade = \App\Cidade::find($request->input('cidade_id'));
        $tenista->cidade()->associate($cidade);
        $academia = \App\Academia::find($request->input('academia_id'));
        $tenista->academia()->associate($academia);
        $classe = \App\Classe::find($request->input('classe_id'));
        $tenista->classe()->associate($classe);
        $date = date_create_from_format('j/m/Y', $request->input('datadenascimento'));
        $tenista->datadenascimento = date_format($date, 'Y-m-d');
        $tenista->sexo = $request->input('sexo');
        $statustenista = \App\Statustenista::find($request->input('statustenista_id'));
        $tenista->statustenista()->associate($statustenista);
//          dd($tenista);
        //\App\Cliente::find($id)->addTenista($tenista);
        $tenista->save();

        \Session::flash('flash_message',[
            'msg'=>"Tenista adicionado com Sucesso!",
            'class'=>"alert-success"
        ]);

        return redirect()->route('tenista.adicionar');
        
    }

       public function editar($id)
    {
        $tenista = \App\Tenista::find($id);
        if(!$tenista){
            \Session::flash('flash_message',[
                'msg'=>"Não existe esse tenista cadastrado! Deseja cadastrar um novo tenista?",
                'class'=>"alert-danger"
            ]);
            return redirect()->route('tenista.adicionar');
        }

        return view('tenista.editar',compact('tenista'));
    }

    public function deletar($id)
    {
        $tenista = \App\Tenista::find($id);

        $tenista->delete();

        \Session::flash('flash_message',[
            'msg'=>"Tenista deletado com Sucesso!",
            'class'=>"alert-success"
        ]);

        return redirect()->route('tenista.adicionar'); 
    }

     public function lista()
    {   
        $tenistas = \App\Tenista::paginate(10);
        return view('tenista.lista', compact('tenistas'));
    }
     
    public function detalhe($id)
    {   
        $tenista = \App\Tenista::find($id);
        return view('tenista.detalhe', compact('tenista'));
    }

}




