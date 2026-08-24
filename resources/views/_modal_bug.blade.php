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
                        <label for="exampleFormControlTextarea1" class="form-label">URL</label>
                        <input type="text" readonly class="form-control" name="url" id="url" value="{{Request::url()}}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Type Of Feedback</label>
                        <div class="form-check ms-4">
                            <input class="form-check-input" type="radio" name="bug_type" id="bug_type_1" value="1">
                            <label class="form-check-label" for="flexRadioDefault1">
                              Bug
                            </label>
                          </div>
                          <div class="form-check ms-4">
                            <input class="form-check-input" type="radio" name="bug_type" id="bug_type2" value="2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                              New Feature
                            </label>
                          </div>
                        @error('bug_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Feedback</label>
                        <textarea class="form-control" name="feedback" id="feedback" rows="10"
                        placeholder="Please enter what issue you found and the steps to reproduce the issue"
                        ></textarea>
                        @error('feedback')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="reporter_email" id="reporter_email" value="{{ Auth::user()->email ?? '' }}">
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
