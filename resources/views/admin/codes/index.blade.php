@extends('layouts.admin')
@section('content')

<form action="{{ url('admin/code/export') }}" method="POST" class="row">@csrf
    <div class="options-row">
        <div class="pr-2"><a href="{{ url('admin/code/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> &nbsp;Ajouter</a></div>
        <div>
            <select class="form-control form-options" name="year">
                @foreach($years as $y)
                    <option value="{{ $y->year }}">{{ $y->year }}</option>
                @endforeach
            </select>
        </div>
        <div><button class="btn btn-secondary ml-2">Exporter</button></div>
    </div>
</form>


<div class="row">
    <div class="col-md-12">

        <div class="panel panel-midnightblue">
            <div class="panel-heading">
                <h4>Codes d'accès</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" style="margin-bottom: 0px;" id="generic">
                        <thead>
                        <tr>
                            <th class="col-sm-2">Action</th>
                            <th class="col-sm-2">Code</th>
                            <th class="col-sm-2">Validité</th>
                            <th class="col-sm-1">Utilisé</th>
                            <th class="col-sm-3">Utilisateur</th>
                            <th class="col-sm-2"></th>
                        </tr>
                        </thead>
                        <tbody class="selects">

                        @if(!empty($codes))
                            @foreach($codes as $code)
                                <tr>
                                    <td><a class="btn btn-sky btn-sm" href="{{ url('admin/code/'.$code->id) }}">&Eacute;diter</a></td>
                                    <td><strong>{{ $code->code }}</strong></td>
                                    <td><strong>{{ $code->valid_at->format('Y-m-d') }}</strong></td>
                                    <td>
                                        {!! $code->used ? '<span class="label label-warning">Utilisé</span>' : '<span class="label label-info">Pas utilisé</span>' !!}
                                    </td>
                                    <td>
                                        @if($code->user_id)
                                            {!! $code->user->name !!}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ url('admin/code/'.$code->id) }}" method="POST">
                                            <input type="hidden" name="_method" value="DELETE">{!! csrf_field() !!}
                                            <button data-action="code: {{ $code->code  }}" class="btn btn-danger btn-sm deleteAction">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@stop
