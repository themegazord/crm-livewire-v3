<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\Permissao;
use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
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
  public array $permissaoConsulta = [];
  public array $sortBy = ['column' => 'id', 'direction' => 'asc'];
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
    $this->validate(rules: ['permissaoConsulta' => 'exists:permissoes,id'], messages: [
      'permissaoConsulta.exists' => 'A permissão não existe.'
    ]);
    $usuarios = User::query()
      ->select(['users.id', 'users.name', 'users.email'])
      ->when($this->permissaoConsulta, function ($query) {
        $query->whereHas('permissoes', function ($subQuery) {
          $subQuery->whereIn('id', $this->permissaoConsulta);
        });
      })
      ->when($this->consulta, function ($query) {
        $query->where(function ($q) {
          $q->where('users.id', 'like', '%' . $this->consulta . '%')
            ->orWhere('users.name', 'like', '%' . $this->consulta . '%')
            ->orWhere('users.email', 'like', '%' . $this->consulta . '%');
        });
      })
      ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
      ->paginate($this->perPage);

    return $usuarios;
  }


  #[Computed]
  public function headers(): array
  {
    return [
      ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
      ['key' => 'name', 'label' => 'Nome do usuário'],
      ['key' => 'email', 'label' => 'Email do usuário'],
      ['key' => 'permissoes', 'label' => 'Permissões', 'sortable' => false]
    ];
  }

  public function permissoes(): Collection
  {
    return Permissao::all();
  }

  public function abrirModalPermissoes(int $usuarioId)
  {
    $this->usuarioPermissoes = User::find($usuarioId)->permissoes; // Carrega permissões
    $this->modalPermissao = true; // Abre o modal
  }
}
