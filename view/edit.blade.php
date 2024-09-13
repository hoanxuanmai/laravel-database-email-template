@extends('database_email_template::layout')

@section('content')

<div class="container-fluid">
    <form id="email-templates-form" action="{{ \HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate::getRouteByName('update', $template) }}" method="POST">
        @method('PUT')
        <input type="hidden" name="id" value="{{ $template->id }}">
        @include('database_email_template::_form')
        <div class="form-group text-center mt-5">

            @include('database_email_template::buttons.default', ['button' => true])
            @include('database_email_template::buttons.preview', ['button' => true])

        </div>
    </form>
</div>
@endsection


