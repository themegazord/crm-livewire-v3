<div class="flex flex-col">
  <x-header title="Listagem de usuários" separator />
  <div class="mb-4 flex items-end space-x-4">
    <div class="w-1/3">
      <x-input icon="o-magnifying-glass" label="Insira o id, nome ou email de algum usuário que deseja consultar" wire:model.live="consulta" placeholder="Id, nome, email..." clearable />
    </div>
    <div class="w-1/3">
      <x-choices
        wire:model.live="permissaoConsulta"
        :options="$usuarioPermissoes"
        option-label="permissao"
        placeholder="Procure a permissão"
        search-function="consultaPermissao"
        no-result-text="Ops! Não existe essa permissão"
        searchable />
    </div>
    <div class="w-1/3">
      <x-checkbox label="Deletados?" wire:model.live="consultaDeletados" class="checkbox-warning" right tight />
    </div>
  </div>
  <x-table
    :headers="$this->headers()"
    :rows="$this->usuarios()"
    with-pagination
    striped
    per-page="perPage"
    :sort-by="$sortBy"
    :per-page-values="[5, 15, 25, 50]">

    @scope('cell_permissoes', $usuario)
    @foreach ($usuario->permissoes as $permissao)
    <x-badge :value="$permissao->permissao" class="badge-primary" />
    @endforeach
    @endscope

    @scope('actions', $usuario)
    @unless ($usuario->trashed())
    @unless ($usuario->is(auth()->user()))
    <x-button icon="o-trash" wire:click="destroy({{ $usuario->id }})" spinner class="btn-sm"></x-button>
    @endunless
    @else
    <x-button icon="o-arrow-path-rounded-square" wire:click="deletarUsuario({{ $usuario->id }})" spinner class="btn-sm btn-success btn-ghost"></x-button>
    @endunless
    @endscope
  </x-table>

  <livewire:admin.usuarios.remover />
</div>
