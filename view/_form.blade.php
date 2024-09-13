@php
    isset($emailsList) || $emailsList = collect();
@endphp
@csrf
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label>Mailable</label>
            <select name="mailable" class="form-control" {{ $template->id ? 'disabled': '' }}>
                @foreach (\HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate::getMailablesList() as $mailable)
                <option value="{{ $mailable }}" {{ $emailsList->contains('mailable', $mailable) ? 'disabled': '' }} {{ old('mailable', $template->mailable) == $mailable ? 'selected' : '' }}>{{ $mailable }}</option>
                @endforeach
            </select>
            @error('mailable')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label>Template type</label>
            <select name="type" class="form-control">
                @foreach (\HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate::getEmailTemplateTypes() as $type => $name)
                <option value="{{ $type }}" {{ old('type', $template->type) == $type ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('type')
                <small class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>
<div class="form-group">
    <label>Subject</label>
    <input type="text" class="form-control" value="{{ old('subject', $template->subject) }}" name="subject">
    @error('subject')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>
<div class="form-group">
    <label class="btn-link focus" style="cursor: pointer" onclick="toggleVars(event)" role="button" aria-expanded="false" aria-controls="varsContent">Vars</label>
    <pre class="vars collapse" id="varsContent">
    </pre>
</div>
<div class="form-group">
    <label>Content</label>
    <textarea name="content" class="form-control" rows="10"onkeydown="if(event.keyCode===9){var v=this.value,s=this.selectionStart,e=this.selectionEnd;this.value=v.substring(0, s)+'\t'+v.substring(e);this.selectionStart=this.selectionEnd=s+1;return false;}"></textarea>
    @error('content')
        <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>
<div>
    @error('render')
        <div class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>
@push('scripts-bottom')
<script>
    @if($content = old('content', $template->content))
        document.querySelector('#email-templates-form textarea').value = @json($content);
    @endif
    let loadVars = false;
    function loadDefaultContent(mailable, show = null) {
        if (! mailable) return alert('Please select one Mailable');
        $.ajax({
            url: @json(\HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate::getRouteByName('loadDefaultData')),
            method: 'POST',
            data: {mailable, _token: document.querySelector('#email-templates-form [name=_token]').value},
        }).done(function(res) {
            if (show == null || show == 'content') document.querySelector('#email-templates-form textarea').value = res.content;
            if (show == null || show == 'vars') {
                document.querySelector('#email-templates-form #varsContent').innerHTML = res.vars;
                loadVars = true;
            }
        })
        .fail(function(data) {
            console.log(data)
            alert(data?.responseJSON?.message || 'load data default error');
        })
    }

    function toggleVars(e) {
        e.preventDefault();
        if(!loadVars) loadDefaultContent(document.querySelector('#email-templates-form [name=mailable]')?.value || @json($template->mailable), 'vars');
        loadVars = true;
        $('.vars.collapse').toggle()
    }
</script>
@endpush
