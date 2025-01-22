<!-- Modal -->
<div class="modal fade" id="bugModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter your feedback</h5>
                    <button type="button" class="btn-close" onclick="closeBugNotes()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Feedback</label>
                        <textarea class="form-control" name="feedback" id="feedback" rows="10"></textarea>
                        @error('feedback')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                </div>
                <div class="modal-footer">
                    <button type="button" id="myCloseModalButtonBug" wire:click="closeBugNotes"
                        class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="myCreateBugButton" onclick="closeModalAndCreateBug()"
                        class="btn btn-success" data-bs-dismiss="modal">Add Feedback</button>
                </div>
            </div>
        </div>
</div>
