<form class="form-inline" role="form" method="POST" action="{{ url('terms') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="content" value="{{ $content }}">
    @if ($content == "arret")    
    <div class="form-group col-md-9 col-search col-small-input">    
       	<loi-global-search :pages="{{ json_encode($pages) }}" :annees="{{ json_encode($years) }}" fieldname="terms"></loi-global-search>
    </div>
    @else
    <div class="form-group col-md-9 col-search col-small-input">
     	<input type="text" name="terms" placeholder="Mots clés" class="form-control search_input">
    </div>
    @endif
    <button type="submit" class="btn btn-danger">OK</button>
</form>