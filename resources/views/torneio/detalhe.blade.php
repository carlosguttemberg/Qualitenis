@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <ol class="breadcrumb panel-heading">
                    <li><a href="{{ route('torneio.index') }}">Torneios</a></li>
                    <li class="active">Detalhe</li>
                </ol>

                <div class="panel-body">
                @if($torneio->statustorneio->id == 1)
                <form action="{{route('torneio.atualizar', $torneio->id)}}" method="POST">
                    {{csrf_field()}}
                           <input type="hidden" name="_method" value="put" placeholder="">
                    <p><b>Torneio: </b></p>
                    <p><b>Preço da inscrição: </b><input type="text" name="precodainscricao" value="{{ $torneio->precodainscricao }}" >
                    @if($errors->has('precodainscricao'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('precodainscricao') }}</strong>
                                </span>
                            @endif
                            </p>
                    
                    <p><b>Data: </b><input type="text" name="data" value="{{date_format(date_create_from_format('Y-m-d',  $torneio->data), 'd/m/Y')}}">
                    @if($errors->has('data'))
                        <span class="help-block">
                            <strong>{{ $errors->first('data') }}</strong>
                        </span>
                    @endif
                    </p>
                    <p><b>Cidade: </b>{{Form::select('cidade_id', \App\Estado::find(19)->cidades->lists('nome', 'id'), $torneio->cidade->id)}}</p>
                    <p><b>Status: </b>{{ $torneio->statustorneio->nome }}
                    </p>
                    <p><b>Informações: </b><textarea name="informacoes">{{$torneio->informacoes }}</textarea></p>
                    <input type="hidden" name="statustorneio_id" value="{{$torneio->statustorneio->id}}">
                    <button class="btn btn-infto">Atualizar</button>
                </form>
                @else
                   <p><b>Torneio: </b></p>
                   <p><b>Preço da inscrição: {{ $torneio->precodainscricao }}</b></p>
                   <p><b>Data: {{date_format(date_create_from_format('Y-m-d',  $torneio->data), 'd/m/Y')}}</b></p>
                   <p><b>Cidade: {{$torneio->cidade->nome}}</b></p>
                   <p><b>Status: {{ $torneio->statustorneio->nome }}</b></p>
                   <p><b>Informações: {{$torneio->informacoes }}</b></p>
                @endif
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Chaveamento</th>
                                <th>Jogadores</th>                                
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>                           
                            @foreach($torneio->chaveamentos as $chaveamento)
                            <tr>
                                <th scope="row">{{ $chaveamento->id }}</th>
                                <td>{{ $chaveamento->classe->nome }}</td>
                                <td>{{ $chaveamento->numerodejogadores }}</td>                                
                                <td>
                                    <a class="btn btn-default" href="{{ route('torneio.chaveamento.editar', ['torneio' => $torneio->id, 'chaveamento' => $chaveamento->id]) }}" {{ $torneio->statustorneio->id == 1 ? '' : 'disabled="true"' }}>Editar</a>
                                    <a class="btn btn-danger" href="javascript:(confirm('Deletar esse registro?') ? window.location.href='{{ route('chaveamento.deletar', ['torneio' => $torneio->id, 'chaveamento' => $chaveamento->id]) }}' : false)" {{ $torneio->statustorneio->id == 1 ? '' : 'disabled="true"' }}>Deletar</a>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                        
                    </table>

                    <p>
                        <a class="btn btn-info" href="{{route('chaveamento.adicionar', $torneio->id)}}">Adicionar chaveamento</a>
                    </p>
                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection