@extends('layouts.admin.main')

@section('breadcrumb', 'Endpoint Cron Task')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <table id="dataTbl" class="table table-responsive"
                        data-ajax="{{ route('setting.endpoint-cront-task.getData') }}" data-processing="true"
                        data-server-side="true" data-length-menu="[10, 25, 50, 75, 100]" data-ordering="true"
                        data-col-reorder="true">
                        <thead>
                            <tr>
                                <th data-data="DT_RowIndex" data-orderable="false" data-searchable="false">No</th>
                                <th data-data="method">Method</th>
                                <th data-data="uri">URI</th>
                                <th data-data="name">Name</th>
                                <th data-data="action">Controller@Method</th>
                                <th data-data="action" data-orderable="false" data-searchable="false"
                                    data-class-name="text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
