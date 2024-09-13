<button class="btn btn-info btn-load-default">Load Default</button>



@push('scripts-bottom')
<script>
    document.querySelector('#email-templates-form .btn-load-default').addEventListener('click', function(e){
        e.preventDefault();
        loadDefaultContent(document.querySelector('#email-templates-form [name="mailable"]').value || @json($template->mailable))
    })

</script>
@endpush
