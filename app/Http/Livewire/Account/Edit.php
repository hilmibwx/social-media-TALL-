<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $name, $username;
    public $picture;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->username = auth()->user()->username;
    }

    public function updated($field)
    {
         $this->validateOnly($field, [
            'username'  => 'min:3|max:25|unique:users,username,' . auth()->id(),
            'name'      => 'min:3|string'
        ]);
    }
    public function update()
    {
        $this->validate([
            'picture' => $this->picture ? 'image|mimes:jpg,jpeg,png' : '',
            'username' => 'required|min:3|max:25|unique:users,username,' . auth()->id(),
            'name' => 'required|min:3|string',
        ]);

        if($this->picture)
        {
            \Storage::delete(auth()->user()->picture);
            $picture = $this->picture->store('images/profile');
        } else {
            $picture = auth()->user()->picture ?? null;
        }
        
        auth()->user()->update([
            'name' => $this->name,
            'username' => $this->username,
            'picture' => $picture
        ]);

        return redirect()->to('settings');
    }
    
    public function render()
    {
        return view('livewire.account.edit');
    }
}