@extends('layouts.admin')
@section('content')

<div class="row"><!-- row -->
    <div class="col-md-12"><!-- col -->
        <p><a class="btn btn-default" href="{{ url('admin/user') }}"><i class="fa fa-reply"></i> &nbsp;Retour à la liste</a></p>
    </div>
</div>
<!-- start row -->
<div class="row">

    @if ( !empty($user) )

    <div class="col-md-12">
        <div class="panel panel-midnightblue">

            <form action="{{ url('admin/user/'.$user->id) }}" method="post" class="form-validation form-horizontal">
                <input type="hidden" name="_method" value="PUT">{!! csrf_field() !!}

            <div class="panel-heading">
                <h4>&Eacute;diter {{ $user->name }}</h4>
            </div>
            <div class="panel-body event-info">

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Nom</label>
                    <div class="col-sm-3">
                        {!! Form::text('name', $user->name , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Email</label>
                    <div class="col-sm-3">
                        {!! Form::text('email', $user->email , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Rôle</label>
                    <div class="col-lg-3 col-sm-5 col-xs-9">
                        <select class="form-control" name="role">
                            <option <?php echo ($user->role == 'abonne' ? 'selected' : ''); ?> value="abonne">Abonné RJN</option>
                            <option <?php echo ($user->role == 'invite' ? 'selected' : ''); ?> value="invite">Invité (consulter le site sans abo)</option>
                            <option <?php echo ($user->role == 'admin' ? 'selected' : ''); ?> value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Numéro (pour abonné)</label>
                    <div class="col-sm-3">
                        {!! Form::text('numero',  $user->numero , array('class' => 'form-control') ) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="message" class="col-sm-3 control-label">Mot de passe</label>
                    <div class="col-sm-3">
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

    @endif

</div>
<!-- end row -->

@stop