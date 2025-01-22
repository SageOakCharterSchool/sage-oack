<div>
    <!-- Modal -->
    <div wire:model="openModal1" wire:ignore.self class="modal fade" id="fileUploadInfo1" tabindex="-1" role="dialog" aria-labelledby="FileUploadInfo1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">File <b>{{$table}}</b> requirements</h5>
                    <button type="button" wire:click="closeThisModal1"  class="close " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Expected Columns: {{$expectedFields}}</h6>
                    <table class="table ">
                        @foreach ($modelInfo as $field => $description)
                            <tr>
                                <td class="width:20%">{{$field}}</th>
                                <td class="width:80%">{{$description}}</th>
                            </tr>

                        @endforeach
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeThisModal1" class="btn btn-secondary " data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div wire:model="openModal2" wire:ignore.self class="modal fade" id="fileUploadInfo2" tabindex="-1" role="dialog" aria-labelledby="FileUploadInfo2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">File <b>{{$table}}</b> requirements</h5>
                    <button type="button" wire:click="closeThisModal2"  class="close " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Last Uploaded: {{$lastUpload->created_at ?? ""}}</h6>
                    <table class="table ">

                            <tr>
                                <td class="width:80%">Uploaded By</th>
                                <td class="width:20%">{{$uploadedBy ?? ""}}</th>
                            </tr>
                            <tr>
                                <td class="width:80%">Columns Expected</th>
                                <td class="width:20%">{{$lastUpload->cols_expected ?? ""}}</th>
                            </tr>
                            <tr>
                                <td class="width:80%">Columns Received</th>
                                <td class="width:20%">{{$lastUpload->num_of_cols ?? ""}}</th>
                            </tr>
                            <tr>
                                <td class="width:80%">Number of Records on File</th>
                                <td class="width:20%">{{$lastUpload->num_of_rows ?? ""}}</th>
                            </tr>
                            <tr>
                                <td class="width:80%">Upload Status</th>
                                <td class="width:20%">{{$lastUpload->error_reported ?? ""}}</th>
                            </tr>


                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeThisModal2" class="btn btn-secondary " data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
