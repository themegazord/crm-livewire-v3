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
  </div>
  <x-table
    :headers="$this->headers()"
    :rows="$this->usuarios()"
    with-pagination
    striped
    per-page="perPage"
    :sort-by="$sortBy"
    :per-page-values="[3, 5, 10]">

    @scope('cell_permissoes', $usuario)
    @foreach ($usuario->permissoes as $permissao)
    <x-badge :value="$permissao->permissao" class="badge-primary" />
    @endforeach
    @endscope

    @scope('actions', $usuario)
    <x-button icon="o-trash" wire:click="delete({{ $usuario->id }})" spinner class="btn-sm"></x-button>
    @endscope
  </x-table>
</div>
