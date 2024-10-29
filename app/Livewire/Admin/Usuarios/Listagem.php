<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination;

  public Collection $usuarioPermissoes;
  public string $consulta = '';
  public array $sortBy = ['column' => 'name', 'direction' => 'asc'];
  public int $perPage = 10;
  public bool $modalPermissao = false;

  public function mount()
  {
    $this->usuarioPermissoes = collect(); // Inicializa como coleção vazia
  }


  public function render()
  {
    return view('livewire.admin.usuarios.listagem');
  }

  #[Computed]
  public function usuarios(): LengthAwarePaginator
  {
    return User::query()
      ->select([
        'id',
        'name',
        'email'
      ])
      ->where('id', 'like', '%' . $this->consulta . '%')
      ->orWhere('name', 'like', '%' . $this->consulta . '%')
      ->orWhere('email', 'like', '%' . $this->consulta . '%')
      ->orderBy(...array_values($this->sortBy))
      ->paginate($this->perPage);
  }

  #[Computed]
  public function headers(): array
  {
    return [
      ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
      ['key' => 'name', 'label' => 'Nome do usuário'],
      ['key' => 'email', 'label' => 'Email do usuário'],
      ['key' => 'permissoes', 'label' => 'Permissões']
    ];
  }

  public function abrirModalPermissoes(int $usuarioId)
  {
    $this->usuarioPermissoes = User::find($usuarioId)->permissoes; // Carrega permissões
    $this->modalPermissao = true; // Abre o modal
  }
}
