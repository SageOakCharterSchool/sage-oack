<?php

namespace App\Http\Livewire\Admin\Cycle;

use App\Models\Cycle;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cycleId;

    public function render()
    {
        $cycles = Cycle::orderBy('id','DESC')->paginate(10);
        return view('livewire.admin.cycle.index', ['cycles' => $cycles]);
    }

    public function destroyCycle() {
        $cycle = Cycle::find($this->cycleId);
        $cycle->delete();
        session()->flash('message','Cycle Deleted');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function deleteCycle($cycleId) {
        $this->cycleId = $cycleId;
    }
}
