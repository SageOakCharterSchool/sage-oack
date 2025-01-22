<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\GlobalActions;
use App\Models\FileUploads;
use App\Models\User;

class ShowUploadsInfo extends Component
{
    public $listeners = ['showFileUploadInfo','showLastUploadInfo','exportFileUploadInfoCSV'];
    public $openModal1 = false;
    public $openModal2 = false;
    public $modelInfoGlobal = null;
    public $lastUpload = null;
    public $modelInfo = [];
    public $table = "";
    public $uploadedBy = "";
    public $expectedFields = 0;

    public function boot() {
        $this->modelInfoGlobal = (GlobalActions::getModelsExpectedFields());
        //dd($this->modelInfoGlobal );
    }

    public function showFileUploadInfo($table) {
        $this->openModal1 = true;
        $this->openModal2 = false;
        //$this->modelInfo = $this->modelInfoGlobal[$table]['fields'];
        $this->modelInfo = GlobalActions::fullMapping($table);
        //dd($this->modelInfo);
        $this->expectedFields = count($this->modelInfo);
        $this->table = $table;
        $this->dispatchBrowserEvent('launch-show-info-modal');
    }
    public function showLastUploadInfo($table) {
        $this->openModal2 = true;
        $this->openModal1 = false;
        $this->lastUpload = FileUploads::returnLastUploadedFileInfo($table);
        if ($this->lastUpload) {
            $this->uploadedBy = User::find($this->lastUpload->created_by)->name;
        }
        $this->dispatchBrowserEvent('launch-show-last_upload-info-modal');
    }

    public function closeThisModal1() {
        $this->openModal2 = false;
        $this->openModal1 = false;
        $this->modelInfo = [];
        $this->lastUpload = null;
        $this->uploadedBy = null;
        $this->expectedFields = 0;
        $this->dispatchBrowserEvent('closeThisModal1');
    }
    public function closeThisModal2() {
        $this->openModal2 = false;
        $this->openModal1 = false;
        $this->modelInfo = [];
        $this->lastUpload = null;
        $this->uploadedBy = null;
        $this->expectedFields = 0;
        $this->dispatchBrowserEvent('closeThisModal2');
    }
    public function render()
    {

        return view('livewire.admin.show-uploads-info');
    }
}
