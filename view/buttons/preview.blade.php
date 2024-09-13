@if ($button ?? false)
    <button id="btn-preview" class="btn btn-success btn-preview">Preview</button>
@endif


@push('scripts-top')
<div id="email-template-preview" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: min(100%, 800px)">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-email-template">Save</button>
              </div>

        </div>
    </div>
</div>
@endpush

@push('scripts-bottom')
<script>
    document.getElementById('btn-preview').addEventListener('click', function(e){
        e.preventDefault();
        const formData = new FormData(document.getElementById('email-templates-form'))
        formData.set('_method', 'PUT')
        emailTemplatePreview(formData);
    });
    document.getElementById('btn-email-template').addEventListener('click', function(e){
        e.preventDefault();
        document.getElementById('email-templates-form').submit();
    });

    function emailTemplatePreview(data = {}) {
        $('#email-template-preview').modal();
        const options = {};
        if (data instanceof FormData) {
            Object.assign(options, {processData: false, contentType: false})
        }
        $.ajax({
            url: @json(\HXM\LaravelDatabaseEmailTemplate\Facades\DatabaseEmailTemplate::getRouteByName('preview')),
            method: 'POST',
            data,
            ...options
        }).done(function(res) {
            document.querySelector('#email-template-preview .modal-body').innerHTML = res;
        })
        .fail(function(data) {
            console.log(data)
        })
    }
</script>
@endpush
