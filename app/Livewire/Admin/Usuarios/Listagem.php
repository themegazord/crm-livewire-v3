<?php

namespace App\Livewire\Admin\Usuarios;

use App\Enum\Pode;
use App\Models\Permissao;
use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Listagem extends Component
{
  use WithPagination;

  public Collection $usuarioPermissoes;
  public ?int $permissaoIdConsultavel = null;

  public string $consulta = '';
  public array $permissaoConsulta = [];
  public bool $consultaDeletados = false;

  public array $sortBy = ['column' => 'id', 'direction' => 'asc'];
  public int $perPage = 15;
  public bool $modalPermissao = false;

  public function mount()
  {
    $this->authorize(Pode::SER_UM_ADMIN->value);
    $this->consultaPermissao();
  }


  public function render()
  {
    return view('livewire.admin.usuarios.listagem');
  }

  public function updatedPerPage($value): void {
    $this->resetPage();
  }

  #[Computed]
  public function usuarios(): LengthAwarePaginator
  {
    $this->validate(rules: ['permissaoConsulta' => 'exists:permissoes,id'], messages: [
      'permissaoConsulta.exists' => 'A permissão não existe.'
    ]);
    $usuarios = User::query()
      ->with('permissoes')
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
      ->when($this->consultaDeletados, fn (Builder $builder) => $builder->onlyTrashed())
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

  public function consultaPermissao(string $valor = ''): void
  {
    $opcaoSelecionada = Permissao::where('id', $this->permissaoIdConsultavel)->get();

    $this->usuarioPermissoes = Permissao::query()
      ->where('permissao', 'like', "%$valor%")
      ->orderBy('permissao')
      ->get()
      ->merge($opcaoSelecionada);
  }

  public function deletarUsuario(int $usuario_id): void {
    if (User::withTrashed()->find($usuario_id)->trashed()) {
      User::withTrashed()->find($usuario_id)->restore();
    } else {
      User::find($usuario_id)->delete();
    }
  }
}
