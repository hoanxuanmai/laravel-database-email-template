@extends('database_email_template::layout')

@section('content')

<div class="container-fluid">
    <form id="email-templates-form"  action="{{ \HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate::getRouteByName('store') }}" method="POST">
        @include('database_email_template::_form')

        <div class="form-group text-center">
            @include('database_email_template::buttons.default', ['button' => true])
            @include('database_email_template::buttons.preview', ['button' => true])
        </div>
    </form>
</div>
@endsection

@push('scripts-bottom')
<script>
    const selector = document.querySelector('#email-templates-form [name="mailable"]');
    selector.addEventListener('change', function(e){
        loadDefaultContent(e.target.value)
    })
    @if (!old('content'))
    if (selector.value)  loadDefaultContent(selector.value)
    @endif

</script>
@endpush
