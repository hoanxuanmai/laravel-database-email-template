@extends('database_email_template::layout')
@section('content')
    <div class="table-container">

        <table id="table-log" class="table table-striped">
            <thead>
                <tr>


                    <th>Mailable</th>
                    <th>Subject</th>
                    <th>type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($templates as $template)
                    <tr>
                        <td>{{ $template->mailable }}</td>
                        <td>{{ $template->subject }}</td>
                        <td>{{ $template->type }}</td>
                        <td>
                            <a class="btn btn-sm btn-primary"
                                href="{{ route(config('database_email_template.route.prefix') . '.edit', $template) }}"><i
                                    class="fas fa-edit"></i></a>
                            <a class="btn btn-sm btn-info" target="_blank"
                                href="{{ route(config('database_email_template.route.prefix') . '.show', $template) }}"><i
                                    class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

    </div>


    <!-- Datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.table-container tr').on('click', function() {
                $('#' + $(this).data('display')).toggle();
            });
            $('#table-log').DataTable({});
        });
    </script>
@endsection
