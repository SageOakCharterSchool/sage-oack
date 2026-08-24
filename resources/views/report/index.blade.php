@extends('layouts.admin')

@section('template_title')
    Reports
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Reports') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('build-reports.create') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

                                        <th>Report Name</th>
                                        <th>Report Description</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        @php
                                            $hasPermissions = \App\Models\ReportPermission::evaluatePermissions($report->id);
                                            if (!$hasPermissions) {
                                                continue;
                                            }
                                            $reportPermissions = \App\Models\ReportPermission::getReportPermissions($report->id);
                                            $reportPermissionsArray = [];
                                            if ($reportPermissions) {
                                                $reportPermissionsArray = explode(",",$reportPermissions->permissions);
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $report->report_name }}</td>
                                            <td>{{ $report->report_description }}</td>

                                            <td>

                                                <form action="{{ route('build-reports.destroy', $report->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="input-group mb-3">
                                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Options</button>
                                                        <ul class="dropdown-menu">
                                                          <li><a class="dropdown-item" href="{{ route('build-reports.show', $report->id) }}">Show</a></li>
                                                          <li><a class="dropdown-item" href="{{ route('build-reports.edit', $report->id) }}">Edit</a></li>
                                                          <li><a class="dropdown-item" href="javascript:openPermissions({{ $report->id }},'{{ $report->report_name }}','{{ $reportPermissions->permissions ?? null }}')">Permissions</a></li>
                                                          <li><hr class="dropdown-divider"></li>
                                                          <li><button class="dropdown-item" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">Delete</button></li>
                                                        </ul>
                                                      </div>
                                                    </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $reports->withQueryString()->links() !!}
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Report Permissions <span
                            id="permissionsReportName"></span></h5>
                    <button type="button" class="close" onclick="closePermissionModal()" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="margin:20px;margin-left:40px;">

                        @foreach (config('constants.userTypes') as $k => $userType)
                            <div class="form-check">
                                <input class="form-check-input reportPermissionValues" type="checkbox"
                                    value="{{ $userType }}" id="permissionUserType_{{ $userType }}">
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{ $k }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="permissionsReportId" id="permissionsReportId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePermissionModal()"
                        data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="savePermissionsReport()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        function openPermissions(reportId, reportName, reportPermissions) {
            $("#permissionsReportName").html(reportName);
            $("#permissionsReportId").val(reportId);
            var myPermissions = reportPermissions.split(",");
            $(".reportPermissionValues").prop("checked", false);
            $(myPermissions).each((key, val) => {
                $("#permissionUserType_" + val).prop("checked", true);
            })
            $('#permissionModal').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#permissionModal').modal("show");
        }

        function closePermissionModal() {
            $('#permissionModal').modal("hide");
        }

        function savePermissionsReport() {
            var selectedPermissions = [];
            $('.reportPermissionValues').each(function() {
                if ($(this).is(":checked")) {
                    selectedPermissions.push($(this).val());
                }
            });
            permissions = selectedPermissions.join(",");
            $.ajax({
                type: 'post',
                url: '/admin/build-reports/update-permissions',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "reportId": $("#permissionsReportId").val(),
                    "permissions": permissions,
                },
                success: function(res) {
                    closePermissionModal();
                    swal({
                        title: "Permissions updated!",
                        text: "Um, couldn't find the fileinput element",
                        icon: "success",
                    });
                    window.location.href = "/admin/build-reports";
                },

            });
        }
    </script>
@endpush
