@extends('layouts.admin')
@section('content')

<div class="row" style="margin-bottom: 20px;"><!-- row -->
    <div class="col-md-8">
        <p><a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
    <div class="col-md-4">
        <p class="text-right"><a class="btn btn-primary" data-toggle="modal" data-target="#addCode" href="#">Ajouter un code</a></p>
    </div>
</div>

@if ( !empty($user) )
<!-- start row -->
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-midnightblue">

            <form action="{{ url('admin/user/'.$user->id) }}" method="post" class="form-validation form-horizontal">
                <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

            <div class="panel-heading">
                <h4>&Eacute;diter {{ $user->name }}</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Nom</label>
                    <div class="col-sm-6">
                        {!! Form::text('name', $user->name , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-6">
                        {!! Form::text('email', $user->email , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Rôle</label>
                    <div class="col-lg-6 col-sm-5 col-xs-9">
                        <select class="form-control" name="role">
                            <option <?php echo ($user->role == 'abonne' ? 'selected' : ''); ?> value="abonne">Abonné RJN</option>
                            <option <?php echo ($user->role == 'invite' ? 'selected' : ''); ?> value="invite">Invité (consulter le site sans abo)</option>
                            <option <?php echo ($user->role == 'admin' ? 'selected' : ''); ?> value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Numéro (pour abonné)</label>
                    <div class="col-sm-6">
                        {!! Form::text('numero',  $user->numero , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Mot de passe</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>

            </div>
            <div class="panel-footer mini-footer ">
                {!! Form::hidden('id', $user->id ) !!}
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <button class="btn btn-primary" type="submit">Envoyer </button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <h4 class="mt-2">Code utilisés</h4>
        @if(!$user->codes->isEmpty())
            @foreach($user->codes->sortByDesc('valid_at') as $code)
                <div class="panel panel-default">
                    <div class="panel-body relative {{ $code->valid_at->isFuture() ? 'bg-active' : 'bg-ligth' }}">
                        <h4>{{ $code->code }}</h4>
                        <p>Valide jusqu'au {{ $code->valid_at->format('d/m/Y') }}</p>
                        <div class="absolute"><i class="font-15 fa {{ $code->valid_at->isFuture() ? 'fa-check text-success' : 'fa-times text-danger' }}"></i></div>

                        <form class="text-right" action="{{ url('admin/removeCode') }}" method="post">{!! csrf_field() !!}
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <input type="hidden" name="code_id" value="{{ $code->id }}">
                            <button type="submit" class="btn btn-danger btn-danger-text">retirer</button>
                        </form>

                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCode" tabindex="-1" aria-labelledby="addCode" aria-hidden="true">
    <div class="modal-dialog" style="width: 300px;">
        <div class="modal-content">
            <form action="{{ url('admin/addCode') }}" method="post">{!! csrf_field() !!}
                <div class="modal-header modal-header-code">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body modal-body-code">
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Code</label>
                        <input type="text" class="form-control" name="code">
                    </div>
                </div>
                <div class="modal-footer-flex">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif

@stop
